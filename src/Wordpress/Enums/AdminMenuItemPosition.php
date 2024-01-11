<?php declare(strict_types=1);

namespace Wordless\Wordpress\Enums;

enum AdminMenuItemPosition: int
{
    case AFTER_DASHBOARD = 2;
    case AFTER_FIRST_SEPARATOR = 4;
    case AFTER_POSTS = 5;
    case AFTER_MEDIA = 10;
    case AFTER_LINKS = 15;
    case AFTER_PAGES = 20;
    case AFTER_COMMENTS = 25;
    case AFTER_SECOND_SEPARATOR = 59;
    case AFTER_APPEARANCE = 60;
    case AFTER_PLUGINS = 65;
    case AFTER_USERS = 70;
    case AFTER_TOOLS = 75;
    case AFTER_SETTINGS = 80;
    case AFTER_THIRD_SEPARATOR = 99;
}
