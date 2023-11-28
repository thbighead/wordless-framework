<?php

namespace Wordless\Infrastructure\Provider\Traits;

use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Wordpress\Listener;

trait ListenersRegistration
{
    /**
     * @return string[]|Listener[]
     */
    public function registerListeners(): array
    {
        return [];
    }

    /*
     * Defines what hooked functions should be removed before they take action.
     * You may define only the hook flag to remove all hooked functions from it
     * (be careful when doing that with WordPress native hooks because its own core uses it):
     *      (in unregisterFilterListeners method) return ['wp_nav_menu_container_allowedtags'],
     * If instead you want to remove a specific function from a hook you may specify it with an array.
     * You may or not specify the priority level. Wordless defaults to 10 if not specified.
     * Just remember that WordPress asks you to specify the exact priority level you used when
     * added the function to action/filter:
     *      (in unregisterFilterListeners method) return [
     *          'content_save_pre' => [
     *              'function' => 'acf_parse_save_blocks',
     *              'priority' => 5,
     *          ],
     *      ],
     * Maybe you need to remove more than one function from the same hook:
     *      (in unregisterActionListeners method) return [
     *          'wp_head' => [
     *              [
     *                  'function' => 'wp_generator',
     *              ],
     *              [
     *                  'function' => 'wp_shortlink_wp_head',
     *              ],
     *              [
     *                  'function' => 'feed_links',
     *                  'priority' => 2,
     *              ],
     *              [
     *                  'function' => 'feed_links_extra',
     *                  'priority' => 3,
     *              ],
     *          ],
     *      ],
     * You may also define a Wordless\Infrastructure\Wordpress\Listener class here as an array key
     * with any value. When doing it, Wordless is going to avoid the class initialization
     * instead of removing it through any WordPress function:
     *      (in unregisterActionListeners method) return [
     *          BootApiControllers::class => 'anything',
     *          HideDiagnosticsFromUserRoles::class => false,
     *          BootHttpRemoteCallsLog::class => null,
     *          HooksDebugLog::class => 16523,
     *      ],
     */

    /**
     * @return RemoveHookDTO[]
     */
    public function unregisterActionListeners(): array
    {
        return [];
    }

    /**
     * @return RemoveHookDTO[]
     */
    public function unregisterFilterListeners(): array
    {
        return [];
    }
}
