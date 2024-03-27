<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;

trait Search
{
    private const KEY_SEARCH = 's';

    public function search(string $word, string ...$words): static
    {
        if (!isset($this->arguments[self::KEY_SEARCH])) {
            $this->arguments[self::KEY_SEARCH] = [];
        }

        foreach (Arr::prepend($words, $word) as $word) {
            $this->arguments[self::KEY_SEARCH][$word] = true;
        }

        return $this;
    }

    public function searchMissing(string $word, string ...$words): static
    {
        if (!isset($this->arguments[self::KEY_SEARCH])) {
            $this->arguments[self::KEY_SEARCH] = [];
        }

        foreach (Arr::prepend($words, $word) as $word) {
            $this->arguments[self::KEY_SEARCH][$word] = false;
        }

        return $this;
    }

    public function searchMissingSortingByRelevance(string $word, string ...$words): static
    {
        $this->searchMissing($word, ...$words)
            ->orderBySearchRelevance();

        return $this;
    }

    public function searchSortingByRelevance(string $word, string ...$words): static
    {
        $this->search($word, ...$words)
            ->orderBySearchRelevance();

        return $this;
    }
}
