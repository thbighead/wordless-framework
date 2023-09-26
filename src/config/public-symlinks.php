<?php

use Wordless\Helpers\Config;

$current_wp_theme = Config::tryToGetOrDefault('wordpress.theme', 'wordless');

return [
    'index.php' => '../wp/index.php',
    'wp-content/plugins' => '../wp/wp-content/plugins!.gitignore',
    "wp-content/themes/$current_wp_theme/public" => "../wp/wp-content/themes/$current_wp_theme/public",
    'wp-content/uploads' => '../wp/wp-content/uploads',
    'wp-core' => '../wp/wp-core!wp-config.php,xmlrpc.php',
];
