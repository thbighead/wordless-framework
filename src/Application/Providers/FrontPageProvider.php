<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Listeners\HideContentEditorForCustomFrontPageAtAdmin;
use Wordless\Application\Listeners\ShowCustomFrontPageAtAdminSideMenu;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\Listener;

class FrontPageProvider extends Provider
{
    /**
     * @return string[]|Listener[]
     */
    public function registerListeners(): array
    {
        return [
            HideContentEditorForCustomFrontPageAtAdmin::class,
            ShowCustomFrontPageAtAdminSideMenu::class,
        ];
    }
}
