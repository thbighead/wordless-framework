<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

trait Search
{
    private const KEY_SEARCH = 's';

    /** @var array<string, bool> $search_words */
    private array $search_words = [];

    public function search(string $word, string ...$words): static
    {
        array_unshift($words, $word);

        foreach ($words as $word) {
            $this->search_words[$word] = true;
        }

        return $this;
    }

    public function searchMissing(string $word, string ...$words): static
    {
        array_unshift($words, $word);

        foreach ($words as $word) {
            $this->search_words[$word] = false;
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

    private function resolveSearch(): void
    {
        foreach ($this->search_words as $word => $is_included) {
            $this->arguments[self::KEY_SEARCH] = isset($this->arguments[self::KEY_SEARCH]) ?
                "{$this->arguments[self::KEY_SEARCH]} " : '';

            $this->arguments[self::KEY_SEARCH] .= $is_included ? $word : "-$word";
        }
    }
}
