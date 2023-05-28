<?php

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Enums;

enum CustomPostTypeMenuPosition: int
{
    case bellow_posts = 5;
    case bellow_media = 10;
    case bellow_links = 15;
    case bellow_pages = 20;
    case bellow_comments = 25;
    case bellow_first_separator = 60;
    case bellow_plugins = 65;
    case bellow_users = 70;
    case bellow_tools = 75;
    case bellow_settings = 80;
    case bellow_second_separator = 100;
}
