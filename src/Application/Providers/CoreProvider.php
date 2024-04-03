<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Commands\ConfigureDateOptions;
use Wordless\Application\Commands\CreateInternalCache;
use Wordless\Application\Commands\GeneratePublicWordpressSymbolicLinks;
use Wordless\Application\Commands\PublishConfigurationFiles;
use Wordless\Application\Commands\PublishWpConfigPhp;
use Wordless\Application\Commands\SyncRoles;
use Wordless\Application\Commands\WordlessInstall;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Application\Listeners\BootApiControllers;
use Wordless\Application\Listeners\BootHttpRemoteCallsLog;
use Wordless\Application\Listeners\ChooseImageEditor;
use Wordless\Application\Listeners\DeferEnqueuedScripts;
use Wordless\Application\Listeners\DisableXmlrpc;
use Wordless\Application\Listeners\DoNotLoadWpAdminBarOutsidePanel;
use Wordless\Application\Listeners\HideContentEditorForCustomFrontPageAtAdmin;
use Wordless\Application\Listeners\HideDiagnosticsFromUserRoles;
use Wordless\Application\Listeners\ManageRestResponseContentTypeHeader;
use Wordless\Application\Listeners\RemoveAdditionalCssFromAdmin;
use Wordless\Application\Listeners\RemoveGlobalCustomInlineStyles;
use Wordless\Application\Listeners\ResolveEnqueues;
use Wordless\Application\Listeners\ShowCustomFrontPageAtAdminSideMenu;
use Wordless\Application\Listeners\WordlessVersionOnAdmin;
use Wordless\Infrastructure\Provider;

class CoreProvider extends Provider
{
    public function registerCommands(): array
    {
        return [
            ConfigureDateOptions::class,
            CreateInternalCache::class,
            GeneratePublicWordpressSymbolicLinks::class,
            PublishConfigurationFiles::class,
            PublishWpConfigPhp::class,
            SyncRoles::class,
            WordlessInstall::class,
            WpCliCaller::class,
        ];
    }

    public function registerListeners(): array
    {
        return [
            BootApiControllers::class,
            BootHttpRemoteCallsLog::class,
            ChooseImageEditor::class,
            DeferEnqueuedScripts::class,
            DisableXmlrpc::class,
            DoNotLoadWpAdminBarOutsidePanel::class,
            HideContentEditorForCustomFrontPageAtAdmin::class,
            HideDiagnosticsFromUserRoles::class,
            ManageRestResponseContentTypeHeader::class,
            RemoveAdditionalCssFromAdmin::class,
            RemoveGlobalCustomInlineStyles::class,
            ResolveEnqueues::class,
            ShowCustomFrontPageAtAdminSideMenu::class,
            WordlessVersionOnAdmin::class,
        ];
    }

    /**
     * @return string[]|Provider[]
     */
    public function registerProviders(): array
    {
        return [
            MakersProvider::class,
            MigrationsProvider::class,
            ScheduleProvider::class,
            UtilityProvider::class,
        ];
    }
}
