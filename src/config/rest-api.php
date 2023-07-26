<?php

use Wordless\Abstractions\Enums\RestApiPolicy;
use Wordless\Abstractions\Enums\RestApiRoutes;

return [
    'enabled' => false,
    'endpoints' => [
        RestApiPolicy::KEY => RestApiPolicy::ALLOW,
        RestApiRoutes::KEY => [
//            '/wp/v2',
//            '/wp/v2/pages' => RestApiRoutes::PUBLIC,
//            '/wp/v2/posts' => RestApiRoutes::AUTH,
        ],
    ],
];
