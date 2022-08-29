<?php

namespace Wordless\Abstractions;

use Wordless\Hookers\AcfAutoGeneratePhpCodeOnSave;
use Wordless\Hookers\AcfLoadLocalGroups;
use Wordless\Hookers\HideAcfAdminPanelAtProduction;

class AcfProvider
{
    public static function addAdditionalHooks(): array
    {
        if (!Composer::isPackageInstalled('infobaseit/acf-pro')) {
            return [];
        }

        return [
            AcfAutoGeneratePhpCodeOnSave::class,
            AcfLoadLocalGroups::class,
            HideAcfAdminPanelAtProduction::class,
        ];
    }
}