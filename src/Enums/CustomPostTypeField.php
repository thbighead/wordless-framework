<?php

namespace Wordless\Enums;

enum CustomPostTypeField: string
{
    case TITLE = 'title';
    case CONTENT = 'editor';
    case AUTHOR = 'author';
    case FEATURED_THUMBNAIL_IMAGE = 'thumbnail';
    case EXCERPT = 'excerpt';
    case TRACK_BACKS = 'trackbacks';
    case CUSTOM = 'custom-fields';
    case COMMENTS = 'comments';
    case REVISIONS = 'revisions';
    case HIERARCHICAL_FIELDS = 'page-attributes';
    case POST_FORMATS = 'post-formats';
}
