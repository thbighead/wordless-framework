<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\OrderBy\Enums;

enum ColumnReference: string
{
    case comment_agent = 'comment_agent';
    case comment_approved = 'comment_approved';
    case comment_author = 'comment_author';
    case comment_author_email = 'comment_author_email';
    case comment_author_ip = 'comment_author_IP';
    case comment_author_url = 'comment_author_url';
    case comment_content = 'comment_content';
    case comment_date = 'comment_date';
    case comment_date_gmt = 'comment_date_gmt';
    case comment_id = 'comment_ID';
    case comment_in = 'comment__in';
    case comment_karma = 'comment_karma';
    case comment_parent = 'comment_parent';
    case comment_post_id = 'comment_post_ID';
    case comment_type = 'comment_type';
    case user_id = 'user_id';
}
