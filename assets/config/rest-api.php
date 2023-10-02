<?php

use Wordless\Abstractions\Enums\RestApiPolicy;
use Wordless\Abstractions\Enums\RestApiRoutes;

return [
    RestApiRoutes::KEY => [
        RestApiRoutes::PUBLIC => [
//            '/wp/v2',
//            '/wp/v2/pages',
//            '/wp/v2/posts',
//            '/wp/v2/users',
        ],
//        RestApiPolicy::ALLOW => [
//
//        ],
        RestApiPolicy::DISALLOW => [

        ],
    ],
];
