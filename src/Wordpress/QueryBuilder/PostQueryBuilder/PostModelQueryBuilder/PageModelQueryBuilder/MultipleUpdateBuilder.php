<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\PageModelQueryBuilder;

use Wordless\Wordpress\Models\Page\Traits\Crud\Traits\CreateAndUpdate\Builder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\MultipleUpdateBuilder\Traits\MultipleUpdateBuilder as BaseMultipleUpdateBuilder;

class MultipleUpdateBuilder extends Builder
{
    use BaseMultipleUpdateBuilder;
}
