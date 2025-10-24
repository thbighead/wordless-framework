<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Models\Page\Traits\Crud\Traits\CreateAndUpdate;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\PageModelQueryBuilder;

class Page extends Post
{
    use CreateAndUpdate;

    final protected const TYPE_KEY = StandardType::page->name;

    public static function query(): PageModelQueryBuilder
    {
        return new PageModelQueryBuilder(static::class);
    }
}
