<?php
/**
 * Plugin Name: Wordless Plugins
 * Plugin URI:  https://packagist.org/packages/thbighead/wordless
 * Description: Wordless plugins or handmade by project maintainer are MUST USE. They can't be removed or disabled
 *              through WP Admin panel. This plugin recursively loads every PHP script inside mu-plugins directory
 *              path. To rehydrate this file just run a composer install or php console mup:loader.
 * Version:     2.0.0
 * Author:      Thales Nathan
 * Author URI:  https://www.soluthions.com
 */

use Wordless\Core\Bootstrapper;

Bootstrapper::boot();

// {include plugins script}
