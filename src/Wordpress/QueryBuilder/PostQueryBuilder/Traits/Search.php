<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Search
{
    /**
     * @param string|string[] $words
     * @param bool $sorted_by_relevance
     * @return PostQueryBuilder
     */
    public function searchFor(string|array $words, bool $sorted_by_relevance = true): PostQueryBuilder
    {
        return $this->search($words, $sorted_by_relevance);
    }

    /**
     * @param string|string[] $words
     * @param bool $sorted_by_relevance
     * @return PostQueryBuilder
     */
    public function searchNotFor(string|array $words, bool $sorted_by_relevance = true): PostQueryBuilder
    {
        return $this->search($words, $sorted_by_relevance, false);
    }

    private function resolveSearch(): void
    {
        foreach ($this->search_words as $word => $is_included) {
            $this->arguments[self::KEY_SEARCH] = isset($this->arguments[self::KEY_SEARCH]) ?
                "{$this->arguments[self::KEY_SEARCH]} " : '';

            $this->arguments[self::KEY_SEARCH] .= $is_included ? $word : "-$word";
        }
    }

    /**
     * @param string|string[] $words
     * @param bool $sorted_by_relevance
     * @param bool $include
     * @return PostQueryBuilder
     */
    private function search(
        string|array $words,
        bool         $sorted_by_relevance = true,
        bool         $include = true
    ): PostQueryBuilder
    {
        if (empty($words)) {
            return $this;
        }

        foreach (Arr::wrap($words) as $word) {
            $this->search_words[$word] = $include;
        }

        if ($sorted_by_relevance) {
            $this->sortBySearchRelevance();
        }

        return $this;
    }
}
