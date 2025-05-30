<?php declare(strict_types=1);

namespace Wordless\Core\Composer\Traits;

use Composer\Package\CompletePackage;
use Composer\Script\Event;

trait PackageDiscovery
{
    protected const WORDLESS_EXTRA_KEY = 'wordless';

    public static function discover(Event $event): void
    {
        /** @var CompletePackage[] $packagesList */
        $packagesList = $event->getComposer()->getRepositoryManager()->getLocalRepository()->getPackages();

        foreach ($packagesList as $package) {
            if (!($wordless_extra_options = self::checkForWordlessExtraOptions($package->getExtra()))) {
                continue;
            }

            self::resolveWordlessOptions($wordless_extra_options, $package, $event);

            $event->getIO()->write("{$package->getName()} discovered as a Wordless package");
        }
    }

    /**
     * @param array $extra_options
     * @return array|false
     */
    private static function checkForWordlessExtraOptions(array $extra_options): false|array
    {
        return is_array($wordless_extra_options = $extra_options[self::WORDLESS_EXTRA_KEY] ?? false) ?
            $wordless_extra_options : false;
    }

    private static function resolveWordlessOptions(array $wordless_extra_options, CompletePackage $package, Event $event): void
    {
        foreach ($wordless_extra_options['scripts'] ?? [] as $wordless_script) {
            $wordless_script($package, $event);
        }
    }
}
