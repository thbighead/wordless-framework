<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\OrderBy\Enums;

enum OrderByColumn: string
{
    case email = 'email';
    case id = 'ID';
    case include = 'include';
    case login = 'login';
    case login_in = 'login__in';
    case name = 'name';
    case nicename = 'nicename';
    case nicename_in = 'nicename__in';
    case post_count = 'post_count';
    case registered = 'registered';
    case url = 'url';
}
