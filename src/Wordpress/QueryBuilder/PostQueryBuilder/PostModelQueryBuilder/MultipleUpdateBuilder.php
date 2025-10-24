<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder;

use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\MultipleUpdateBuilder\Traits\MultipleUpdateBuilder as ClassTrait;

class MultipleUpdateBuilder extends Builder
{
    use ClassTrait;
}
