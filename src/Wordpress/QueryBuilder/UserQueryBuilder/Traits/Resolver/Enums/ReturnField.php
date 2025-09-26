<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver\Enums;

enum ReturnField: string
{
    case display_name = 'display_name';
    case id = 'ID';
    case user_activation_key = 'user_activation_key';
    case user_email = 'user_email';
    case user_login = 'user_login';
    case user_nicename = 'user_nicename';
    case user_pass = 'user_pass';
    case user_registered = 'user_registered';
    case user_status = 'user_status';
    case user_url = 'user_url';
}
