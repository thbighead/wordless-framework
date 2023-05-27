<?php

namespace Wordless\Application\Helpers\Reserved\Enums;

/**
 * https://developer.wordpress.org/reference/functions/register_taxonomy/#reserved-terms
 */
enum Term: string
{
    case action = 'action';
    case attachment = 'attachment';
    case attachment_id = 'attachment_id';
    case author = 'author';
    case author_name = 'author_name';
    case calendar = 'calendar';
    case cat = 'cat';
    case category = 'category';
    case category__and = 'category__and';
    case category__in = 'category__in';
    case category__not_in = 'category__not_in';
    case category_name = 'category_name';
    case comments_per_page = 'comments_per_page';
    case comments_popup = 'comments_popup';
    case custom = 'custom';
    case customize_messenger_channel = 'customize_messenger_channel';
    case customized = 'customized';
    case cpage = 'cpage';
    case day = 'day';
    case debug = 'debug';
    case embed = 'embed';
    case error = 'error';
    case exact = 'exact';
    case feed = 'feed';
    case fields = 'fields';
    case hour = 'hour';
    case link_category = 'link_category';
    case m = 'm';
    case minute = 'minute';
    case monthnum = 'monthnum';
    case more = 'more';
    case name = 'name';
    case nav_menu = 'nav_menu';
    case nonce = 'nonce';
    case nopaging = 'nopaging';
    case offset = 'offset';
    case order = 'order';
    case orderby = 'orderby';
    case p = 'p';
    case page = 'page';
    case page_id = 'page_id';
    case paged = 'paged';
    case pagename = 'pagename';
    case pb = 'pb';
    case perm = 'perm';
    case post = 'post';
    case post__in = 'post__in';
    case post__not_in = 'post__not_in';
    case post_format = 'post_format';
    case post_mime_type = 'post_mime_type';
    case post_status = 'post_status';
    case post_tag = 'post_tag';
    case post_type = 'post_type';
    case posts = 'posts';
    case posts_per_archive_page = 'posts_per_archive_page';
    case posts_per_page = 'posts_per_page';
    case preview = 'preview';
    case robots = 'robots';
    case s = 's';
    case search = 'search';
    case second = 'second';
    case sentence = 'sentence';
    case showposts = 'showposts';
    case static = 'static';
    case status = 'status';
    case subpost = 'subpost';
    case subpost_id = 'subpost_id';
    case tag = 'tag';
    case tag__and = 'tag__and';
    case tag__in = 'tag__in';
    case tag__not_in = 'tag__not_in';
    case tag_id = 'tag_id';
    case tag_slug__and = 'tag_slug__and';
    case tag_slug__in = 'tag_slug__in';
    case taxonomy = 'taxonomy';
    case tb = 'tb';
    case term = 'term';
    case terms = 'terms';
    case theme = 'theme';
    case title = 'title';
    case type = 'type';
    case types = 'types';
    case w = 'w';
    case withcomments = 'withcomments';
    case withoutcomments = 'withoutcomments';
    case year = 'year';
}
