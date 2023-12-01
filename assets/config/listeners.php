<?php /** @noinspection PhpUnhandledExceptionInspection */

use Wordless\Application\Listeners\AllowSvgUpload;
use Wordless\Application\Listeners\BootApiControllers;
use Wordless\Application\Listeners\BootCustomPostTypes;
use Wordless\Application\Listeners\BootCustomTaxonomies;
use Wordless\Application\Listeners\BootHttpRemoteCallsLog;
use Wordless\Application\Listeners\ChooseImageEditor;
use Wordless\Application\Listeners\CustomLoginUrl\RedirectCustomLoginUrl;
use Wordless\Application\Listeners\DeferEnqueuedScripts;
use Wordless\Application\Listeners\DisableCptComments;
use Wordless\Application\Listeners\DisableDefaultComments;
use Wordless\Application\Listeners\DisableXmlrpc;
use Wordless\Application\Listeners\DoNotLoadWpAdminBarOutsidePanel;
use Wordless\Application\Listeners\EnqueueThemeEnqueueables;
use Wordless\Application\Listeners\ForceXmlTagToUploadedSvgFiles;
use Wordless\Application\Listeners\HideContentEditorForCustomFrontPageAtAdmin;
use Wordless\Application\Listeners\HideDiagnosticsFromUserRoles;
use Wordless\Application\Listeners\HooksDebugLog;
use Wordless\Application\Listeners\RestApi\Authentication;
use Wordless\Application\Listeners\RestApi\DefineEndpoints;
use Wordless\Application\Listeners\ShowCustomFrontPageAtAdminSideMenu;
use Wordless\Application\Listeners\WordlessVersionOnAdmin;
use Wordless\Application\Providers\LoginRedirectProvider;
use Wordless\Application\Providers\RestApiProvider;
use Wordless\Application\Providers\WpSpeedUpProvider;
use Wordless\Core\Bootstrapper;

return [
    Bootstrapper::LISTENERS_BOOT_CONFIG_KEY => [
        ...RedirectCustomLoginUrl::addAdditionalHooks(),
        ...RestApiProvider::addAdditionalHooks(),
        ...WpSpeedUpProvider::addAdditionalHooks(),
        AllowSvgUpload::class,
        Authentication::class,
        BootApiControllers::class,
        BootCustomPostTypes::class,
        BootCustomTaxonomies::class,
        BootHttpRemoteCallsLog::class,
        ChooseImageEditor::class,
        DeferEnqueuedScripts::class,
        DefineEndpoints::class,
        DisableCptComments::class,
        DisableDefaultComments::class,
        DisableXmlrpc::class,
        DoNotLoadWpAdminBarOutsidePanel::class,
        EnqueueThemeEnqueueables::class,
        ForceXmlTagToUploadedSvgFiles::class,
        HideContentEditorForCustomFrontPageAtAdmin::class,
        HideDiagnosticsFromUserRoles::class,
        HooksDebugLog::class,
        ShowCustomFrontPageAtAdminSideMenu::class,
        WordlessVersionOnAdmin::class,
    ],

    /**
     * Defines what hooked functions should be removed before they take action.
     * You may define only the hook flag to remove all hooked functions from it
     * (be careful when doing that with WordPress native hooks because its own core uses it):
     *      Bootstrapper::HOOKERS_REMOVE_FILTER_CONFIG_KEY => ['wp_nav_menu_container_allowedtags'],
     * If instead you want to remove a specific function from a hook you may specify it with an array.
     * You may or not specify the priority level. Wordless defaults to 10 if not specified.
     * Just remember that WordPress asks you to specify the exact priority level you used when
     * added the function to action/filter:
     *      Bootstrapper::HOOKERS_REMOVE_FILTER_CONFIG_KEY => [
     *          'content_save_pre' => [
     *              Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => 'acf_parse_save_blocks',
     *              Bootstrapper::HOOKERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY => 5,
     *          ],
     *      ],
     * Maybe you need to remove more than one function from the same hook:
     *      Bootstrapper::HOOKERS_REMOVE_ACTION_CONFIG_KEY => [
     *          'wp_head' => [
     *              [
     *                  Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => 'wp_generator',
     *              ],
     *              [
     *                  Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => 'wp_shortlink_wp_head',
     *              ],
     *              [
     *                  Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => 'feed_links',
     *                  Bootstrapper::HOOKERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY => 2,
     *              ],
     *              [
     *                  Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => 'feed_links_extra',
     *                  Bootstrapper::HOOKERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY => 3,
     *              ],
     *          ],
     *      ],
     * You may also define a Wordless\Abstractions\AbstractHooker class here as an array key
     * with any value. When doing it, Wordless is going to avoid the class initialization
     * instead of removing it through any WordPress function:
     *      Bootstrapper::HOOKERS_REMOVE_ACTION_CONFIG_KEY => [
     *          BootApiControllers::class => 'anything',
     *          HideDiagnosticsFromUserRoles::class => false,
     *          BootHttpRemoteCallsLog::class => null,
     *          HooksDebugLog::class => 16523,
     *      ],
     */
    Bootstrapper::LISTENERS_REMOVE_CONFIG_KEY => [
        Bootstrapper::LISTENERS_REMOVE_ACTION_CONFIG_KEY => array_merge_recursive([
            //
        ], WpSpeedUpProvider::removeActionsConfigToSpeedUp(), LoginRedirectProvider::removeLoginTemplateHook()),
        Bootstrapper::LISTENERS_REMOVE_FILTER_CONFIG_KEY => array_merge_recursive([
            //
        ], WpSpeedUpProvider::removeFiltersConfigToSpeedUp()),
    ],
];
