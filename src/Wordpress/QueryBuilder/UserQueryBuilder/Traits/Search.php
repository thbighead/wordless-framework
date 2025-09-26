<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Search\Enums\SearchColumn;

trait Search
{
    public function search(string $word): static
    {
        $this->arguments['search'] = $word;

        return $this;
    }

    public function searchBy(string $word, SearchColumn $column, SearchColumn ...$columns): static
    {
        $this->arguments['search_columns'] = array_map(function (SearchColumn $column) {
            return $column->value;
        }, Arr::prepend($columns, $column));

        return $this->search($word);
    }
}
