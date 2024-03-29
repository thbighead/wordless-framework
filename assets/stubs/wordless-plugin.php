<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

/**
 * Plugin Name: Wordless Plugins
 * Plugin URI:  https://packagist.org/packages/thbighead/wordless
 * Description: Wordless plugins or handmade by project maintainer are MUST USE. They can't be removed or disabled
 *              through WP Admin panel. This plugin recursively loads every PHP script inside mu-plugins directory
 *              path. To rehydrate this file just run a composer install or php console mup:loader.
 * Version:     2.0.0
 * Author:      Thales Nathan
 * Author URI:  https://github.com/thbighead
 */

use Wordless\Core\Bootstrapper;

Bootstrapper::bootMainPlugin();
