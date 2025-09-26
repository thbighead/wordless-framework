<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Search\Enums;

enum SearchColumn: string
{
    case display_name = 'display_name';
    case id = 'ID';
    case user_email = 'user_email';
    case user_login = 'user_login';
    case user_nicename = 'user_nicename';
    case user_url = 'user_url';
}
