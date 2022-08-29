<?php

namespace Wordless\Abstractions;

use Composer\InstalledVersions;
use Wordless\Hookers\AcfAutoGeneratePhpCodeOnSave;
use Wordless\Hookers\AcfLoadLocalGroups;
use Wordless\Hookers\HideAcfAdminPanelAtProduction;

class AcfProvider
{
    public static function addAdditionalHooks(): array
    {
        if (!InstalledVersions::isInstalled('infobaseit/acf-pro')) {
            return [];
        }

        return [
            AcfAutoGeneratePhpCodeOnSave::class,
            AcfLoadLocalGroups::class,
            HideAcfAdminPanelAtProduction::class,
        ];
    }
}