<?php

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\DTO\FieldsSupportedArrayDTO\Enums;

enum CustomPostTypeFieldSupported: string
{
    case title = 'title';
    case content = 'editor';
    case author = 'author';
    case featured_thumbnail_image = 'thumbnail';
    case excerpt = 'excerpt';
    case track_backs = 'trackbacks';
    case custom = 'custom-fields';
    case comments = 'comments';
    case revisions = 'revisions';
    case hierarchical_fields = 'page-attributes';
    case formats = 'post-formats';
}
