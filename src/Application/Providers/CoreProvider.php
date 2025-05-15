<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Cachers\ConfigCacher;
use Wordless\Application\Cachers\EnvironmentCacher;
use Wordless\Application\Cachers\PluginsCacher;
use Wordless\Application\Commands\CleanInternalCache;
use Wordless\Application\Commands\ConfigureDateOptions;
use Wordless\Application\Commands\CreateInternalCache;
use Wordless\Application\Commands\DistributeFront;
use Wordless\Application\Commands\GeneratePublicWordpressSymbolicLinks;
use Wordless\Application\Commands\PublishConfigurationFiles;
use Wordless\Application\Commands\PublishWpConfigPhp;
use Wordless\Application\Commands\SyncRoles;
use Wordless\Application\Commands\WordlessInstall;
use Wordless\Application\Commands\WordlessLanguages;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Application\Listeners\BootApiControllers;
use Wordless\Application\Listeners\BootHttpRemoteCallsLog;
use Wordless\Application\Listeners\ChooseImageEditor;
use Wordless\Application\Listeners\ClearFrontPageOptionsWhenPageDeleted;
use Wordless\Application\Listeners\ConfigureUrlGuessing;
use Wordless\Application\Listeners\DeferEnqueuedScripts;
use Wordless\Application\Listeners\DisableXmlrpc;
use Wordless\Application\Listeners\DoNotLoadWpAdminBarOutsidePanel;
use Wordless\Application\Listeners\EnableCors;
use Wordless\Application\Listeners\EnableCsp;
use Wordless\Application\Listeners\HideContentEditorForCustomFrontPageAtAdmin;
use Wordless\Application\Listeners\HideDiagnosticsFromUserRoles;
use Wordless\Application\Listeners\ManageRestResponseContentTypeHeader;
use Wordless\Application\Listeners\PageBodyClass;
use Wordless\Application\Listeners\PreventWordlessUserDeletion;
use Wordless\Application\Listeners\RegisterCustomScheduleRecurrences;
use Wordless\Application\Listeners\RegisterEntities;
use Wordless\Application\Listeners\RemoveAdditionalCssFromAdmin;
use Wordless\Application\Listeners\RemoveClassicThemeStylesInlineCss;
use Wordless\Application\Listeners\RemoveGlobalCustomInlineStyles;
use Wordless\Application\Listeners\ResolveAdminEnqueues;
use Wordless\Application\Listeners\ResolveFrontendEnqueues;
use Wordless\Application\Listeners\ShowCustomFrontPageAtAdminSideMenu;
use Wordless\Application\Listeners\WordlessVersionOnAdmin;
use Wordless\Infrastructure\Cacher;
use Wordless\Infrastructure\Provider;

class CoreProvider extends Provider
{
    public function registerCommands(): array
    {
        return [
            ConfigureDateOptions::class,
            CleanInternalCache::class,
            CreateInternalCache::class,
            DistributeFront::class,
            GeneratePublicWordpressSymbolicLinks::class,
            PublishConfigurationFiles::class,
            PublishWpConfigPhp::class,
            SyncRoles::class,
            WordlessInstall::class,
            WordlessLanguages::class,
            WpCliCaller::class,
        ];
    }

    public function registerListeners(): array
    {
        return [
            BootApiControllers::class,
            BootHttpRemoteCallsLog::class,
            ChooseImageEditor::class,
            ClearFrontPageOptionsWhenPageDeleted::class,
            ConfigureUrlGuessing::class,
            DeferEnqueuedScripts::class,
            DisableXmlrpc::class,
            DoNotLoadWpAdminBarOutsidePanel::class,
            EnableCors::class,
            EnableCsp::class,
            HideContentEditorForCustomFrontPageAtAdmin::class,
            HideDiagnosticsFromUserRoles::class,
            ManageRestResponseContentTypeHeader::class,
            PageBodyClass::class,
            PreventWordlessUserDeletion::class,
            RegisterCustomScheduleRecurrences::class,
            RegisterEntities::class,
            RemoveAdditionalCssFromAdmin::class,
            RemoveClassicThemeStylesInlineCss::class,
            RemoveGlobalCustomInlineStyles::class,
            ResolveAdminEnqueues::class,
            ResolveFrontendEnqueues::class,
            ShowCustomFrontPageAtAdminSideMenu::class,
            WordlessVersionOnAdmin::class,
        ];
    }

    /**
     * @return string[]|Cacher[]
     */
    public function registerInternalCachers(): array
    {
        return [
            ConfigCacher::class,
            EnvironmentCacher::class,
            PluginsCacher::class,
        ];
    }

    /**
     * @return string[]|Provider[]
     */
    public function registerProviders(): array
    {
        return [
            AdminBarEnvironmentFlagProvider::class,
            MakersProvider::class,
            MigrationsProvider::class,
            ScheduleProvider::class,
            UtilityProvider::class,
        ];
    }
}
