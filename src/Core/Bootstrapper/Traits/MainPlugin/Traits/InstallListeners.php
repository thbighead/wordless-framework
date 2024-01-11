<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits;

use Wordless\Core\Bootstrapper;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Wordpress\Hook\Enums\Type;

trait InstallListeners
{
    private array $loaded_listeners = [];

    /**
     * @return string[]|Listener[]
     */
    private function getLoadedListeners(): array
    {
        return array_keys($this->loaded_listeners);
    }

    private function loadListeners(Provider $provider): static
    {
        foreach ($provider->registerListeners() as $listener_namespace) {
            $this->loaded_listeners[$listener_namespace] = true;
        }

        return $this;
    }

    private function resolveListeners(): static
    {
        foreach ($this->getLoadedListeners() as $listener_namespace) {
            $listener_namespace::hookIt();
        }

        return $this;
    }

    /**
     * @param RemoveHookDTO[] $removable_actions
     * @return $this
     */
    private function resolveRemovableActions(array $removable_actions): static
    {
        $this->resolveRemovableHooks($removable_actions, Type::action);

        return $this;
    }

    /**
     * @param RemoveHookDTO[] $removable_filters
     * @return $this
     */
    private function resolveRemovableFilters(array $removable_filters): static
    {
        $this->resolveRemovableHooks($removable_filters, Type::filter);

        return $this;
    }

    /**
     * @param RemoveHookDTO[] $removable_hooks
     * @param Type $type
     * @return void
     */
    private function resolveRemovableHooks(array $removable_hooks, Type $type): void
    {
        $remove_all_function = "remove_all_{$type->name}s";
        $remove_single_function = "remove_$type->name";

        foreach ($removable_hooks as $removableHook) {
            if ($removableHook->isOnListener()) {
                unset($this->loaded_listeners[$removableHook->hook]);
                continue;
            }

            $functions_usec_on_hook = $removableHook->getFunctionsUsedOnHook();

            if (empty($functions_usec_on_hook)) {
                $remove_all_function($removableHook->hook);
                continue;
            }

            foreach ($functions_usec_on_hook as $function_used_on_hook) {
                $remove_single_function(
                    $removableHook->hook,
                    $function_used_on_hook[RemoveHookDTO::FUNCTION_KEY],
                    $function_used_on_hook[RemoveHookDTO::PRIORITY_KEY] ?? 10
                );
            }
        }
    }
}
