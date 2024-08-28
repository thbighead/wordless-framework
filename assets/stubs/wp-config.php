<?php /** @noinspection DuplicatedCode */

declare(strict_types=1);

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the website, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
if (!defined('ROOT_PROJECT_PATH')) {
    define('ROOT_PROJECT_PATH', __DIR__ . '/../..');
}

// Nginx TOO_MANY_REDIRECTS error resolution
if (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null) === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

require_once ROOT_PROJECT_PATH . '/vendor/autoload.php';

use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Timezone;
use Wordless\Application\Libraries\LogManager\Logger;
use Wordless\Application\Providers\AdminCustomUrlProvider;
use Wordless\Core\InternalCache;

const WP_DEBUG = true;
// https://wordpress.org/support/article/editing-wp-config-php/#disable-plugin-and-theme-update-and-installation
const DISALLOW_FILE_MODS = true;
// https://wordpress.org/support/article/editing-wp-config-php/#disable-wordpress-auto-updates
const AUTOMATIC_UPDATER_DISABLED = true;
// https://wordpress.org/support/article/editing-wp-config-php/#disable-wordpress-core-updates
const WP_AUTO_UPDATE_CORE = false;
// https://wordpress.org/support/article/editing-wp-config-php/#modify-autosave-interval
const AUTOSAVE_INTERVAL = 60; // Seconds
// https://wordpress.org/support/article/editing-wp-config-php/#specify-the-number-of-post-revisions
const WP_POST_REVISIONS = 10; // Maximum number of a post revisions
// https://developer.wordpress.org/plugins/cron/hooking-wp-cron-into-the-system-task-scheduler/
const DISABLE_WP_CRON = true;

/** @noinspection PhpUnhandledExceptionInspection */
InternalCache::load();

/** @noinspection PhpUnhandledExceptionInspection */
date_default_timezone_set(Timezone::forPhpIni());

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', Environment::get('DB_NAME'));

/** MySQL database username */
define('DB_USER', Environment::get('DB_USER'));

/** MySQL database password */
define('DB_PASSWORD', Environment::get('DB_PASSWORD'));

/** MySQL hostname */
define('DB_HOST', Environment::get('DB_HOST'));

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', Environment::get('DB_CHARSET', 'utf8'));

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', Environment::get('DB_COLLATE', 'utf8_general_ci'));

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', Environment::get('AUTH_KEY'));
define('SECURE_AUTH_KEY', Environment::get('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY', Environment::get('LOGGED_IN_KEY'));
define('NONCE_KEY', Environment::get('NONCE_KEY'));
define('AUTH_SALT', Environment::get('AUTH_SALT'));
define('SECURE_AUTH_SALT', Environment::get('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT', Environment::get('LOGGED_IN_SALT'));
define('NONCE_SALT', Environment::get('NONCE_SALT'));

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
/** @noinspection PhpUnhandledExceptionInspection */
$table_prefix = Environment::get('DB_TABLE_PREFIX', 'wp_');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
// https://wordpress.org/support/article/editing-wp-config-php/#wp_environment_type
define('WP_ENVIRONMENT_TYPE', $environment = Environment::get('APP_ENV', Environment::LOCAL));

// https://wordpress.stackexchange.com/a/340067
const WP_DISABLE_FATAL_ERROR_HANDLER = WP_ENVIRONMENT_TYPE === Environment::LOCAL;
// https://wordpress.org/support/article/editing-wp-config-php/#empty-trash
const EMPTY_TRASH_DAYS = WP_ENVIRONMENT_TYPE === Environment::LOCAL ? 0 : 30;

// https://wordpress.org/support/article/editing-wp-config-php/#configure-error-logging
define('WP_DEBUG_LOG', Logger::getFullTimedPathName());
// https://wordpress.org/support/article/debugging-in-wordpress/#wp_debug_display
// Enabled only when WP_DEBUG_DISPLAY is on in non-production environments and WP_DEBUG_LOG is off, otherwise check logs files.
define(
    'WP_DEBUG_DISPLAY',
    WP_DEBUG && Environment::get('WP_DEBUG_DISPLAY', false) && (WP_ENVIRONMENT_TYPE !== Environment::PRODUCTION)
);

// https://wordpress.org/support/article/editing-wp-config-php/#disable-wordpress-auto-updates
define('COOKIE_DOMAIN', $app_domain = Str::after($site_url = Environment::get('APP_URL'), '://'));

// https://wordpress.org/support/article/editing-wp-config-php/#blog-address-url
define('WP_HOME', $site_url);

// https://wordpress.org/support/article/editing-wp-config-php/#wp_siteurl
$site_url = Str::finishWith($site_url, '/');
define('WP_SITEURL', $site_url . AdminCustomUrlProvider::getCustomUri(false));

// https://wordpress.org/support/article/editing-wp-config-php/#moving-wp-content-folder
define('WP_CONTENT_DIR', realpath(__DIR__ . '/../wp-content'));
define('WP_CONTENT_URL', "{$site_url}wp-content");

// https://wordpress.org/support/article/editing-wp-config-php/#require-ssl-for-admin-and-logins
define('FORCE_SSL_ADMIN', $environment === Environment::PRODUCTION);

/** @noinspection PhpUnhandledExceptionInspection */
$allowed_hosts = Environment::get('WP_ACCESSIBLE_HOSTS', '*.wordpress.org');
if (!empty($allowed_hosts)) {
    /** @noinspection PhpUnhandledExceptionInspection */
    $front_domain = Str::after($site_url = Environment::get('FRONT_END_URL'), '://');
    $accessible_hosts = $front_domain !== $app_domain ?
        "$allowed_hosts,$app_domain,*.$app_domain,$front_domain,*.$front_domain" :
        "$allowed_hosts,$app_domain,*.$app_domain";
    // https://wordpress.org/support/article/editing-wp-config-php/#block-external-url-requests
    define('WP_HTTP_BLOCK_EXTERNAL', true);
    define('WP_ACCESSIBLE_HOSTS', $accessible_hosts);
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
