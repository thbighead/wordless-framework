<?php

namespace Wordless\Abstractions;

use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class Bootstrapper
{
    /**
     * @throws PathNotFoundException
     */
    public static function bootAll()
    {
        $hookers_config = include ProjectPath::config('hookers.php');
        $removable_hooks = $hookers_config['remove'] ?? [];

        self::resolveBootables($hookers_config['boots'] ?? [], $removable_hooks);

        self::resolveRemovableHooks($removable_hooks);
    }

    private static function resolveBootables(array $bootables, array &$removable_hooks)
    {
        foreach ($bootables as $bootable_class_namespace) {
            if ($removable_hooks['action'][$bootable_class_namespace] ?? false) {
                unset($removable_hooks['action'][$bootable_class_namespace]);
                continue;
            }

            if ($removable_hooks['filter'][$bootable_class_namespace] ?? false) {
                unset($removable_hooks['filter'][$bootable_class_namespace]);
                continue;
            }

            /** @var AbstractHooker $bootable_class_namespace */
            $bootable_class_namespace::boot();
        }
    }

    private static function resolveRemovableHooks(array $removable_hooks)
    {
        foreach ($removable_hooks as $hook_type => $removable_hook) {
            $remove_single_hook_function = "remove_$hook_type";
            $remove_all_hook_function = "remove_all_{$hook_type}s";

            foreach ($removable_hook as $hook_flag => $hook_content) {
                if (is_array($hook_content)) {
                    $remove_single_hook_function(
                        $hook_flag,
                        $hook_content['function'],
                        $hook_content['priority'] ?? 10
                    );
                    continue;
                }

                if (is_a($hook_content, AbstractHooker::class, true)) {
                    continue;
                }

                $remove_all_hook_function($hook_content);
            }
        }
    }
}