<?php

namespace Wordless\Application\Providers;

use Wordless\Application\Commands\CreateInternalCache;
use Wordless\Application\Commands\FlushMigrations;
use Wordless\Application\Commands\GeneratePublicWordpressSymbolicLinks;
use Wordless\Application\Commands\MakeCommand;
use Wordless\Application\Commands\MakeController;
use Wordless\Application\Commands\MakeCustomPostType;
use Wordless\Application\Commands\MakeListener;
use Wordless\Application\Commands\MakeMigration;
use Wordless\Application\Commands\Migrate;
use Wordless\Application\Commands\MigrateRollback;
use Wordless\Application\Commands\MigrationList;
use Wordless\Application\Commands\PublishConfigurationFiles;
use Wordless\Application\Commands\ReplaceBaseUrls;
use Wordless\Application\Commands\RunTests;
use Wordless\Application\Commands\SyncRoles;
use Wordless\Application\Commands\WordlessInstall;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Application\Commands\WpHelixShell;
use Wordless\Application\Commands\WpHooksList;
use Wordless\Application\Listeners\AllowSvgUpload;
use Wordless\Application\Listeners\BootApiControllers;
use Wordless\Application\Listeners\BootHttpRemoteCallsLog;
use Wordless\Application\Listeners\ChooseImageEditor;
use Wordless\Application\Listeners\DeferEnqueuedScripts;
use Wordless\Application\Listeners\DisableXmlrpc;
use Wordless\Application\Listeners\DoNotLoadWpAdminBarOutsidePanel;
use Wordless\Application\Listeners\EnqueueThemeEnqueueables;
use Wordless\Application\Listeners\ForceXmlTagToUploadedSvgFiles;
use Wordless\Application\Listeners\HideContentEditorForCustomFrontPageAtAdmin;
use Wordless\Application\Listeners\HideDiagnosticsFromUserRoles;
use Wordless\Application\Listeners\ManageRestResponseContentTypeHeader;
use Wordless\Application\Listeners\RemoveAdditionalCssFromAdmin;
use Wordless\Application\Listeners\RemoveGlobalCustomInlineStyles;
use Wordless\Application\Listeners\ShowCustomFrontPageAtAdminSideMenu;
use Wordless\Application\Listeners\WordlessVersionOnAdmin;
use Wordless\Infrastructure\Provider;

final class CoreProvider extends Provider
{
    public function registerCommands(): array
    {
        return [
            CreateInternalCache::class,
            FlushMigrations::class,
            GeneratePublicWordpressSymbolicLinks::class,
            MakeCommand::class,
            MakeController::class,
            MakeCustomPostType::class,
            MakeListener::class,
            MakeMigration::class,
            Migrate::class,
            MigrateRollback::class,
            MigrationList::class,
            PublishConfigurationFiles::class,
            ReplaceBaseUrls::class,
            RunTests::class,
            SyncRoles::class,
            WordlessInstall::class,
            WpCliCaller::class,
            WpHelixShell::class,
            WpHooksList::class,
        ];
    }

    public function registerListeners(): array
    {
        return [
            AllowSvgUpload::class,
            BootApiControllers::class,
            BootHttpRemoteCallsLog::class,
            ChooseImageEditor::class,
            DeferEnqueuedScripts::class,
            DisableXmlrpc::class,
            DoNotLoadWpAdminBarOutsidePanel::class,
            EnqueueThemeEnqueueables::class,
            ForceXmlTagToUploadedSvgFiles::class,
            HideContentEditorForCustomFrontPageAtAdmin::class,
            HideDiagnosticsFromUserRoles::class,
            ManageRestResponseContentTypeHeader::class,
            RemoveAdditionalCssFromAdmin::class,
            RemoveGlobalCustomInlineStyles::class,
            ShowCustomFrontPageAtAdminSideMenu::class,
            WordlessVersionOnAdmin::class,
        ];
    }
}
