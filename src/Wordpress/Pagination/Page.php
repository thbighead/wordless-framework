<?php

namespace Wordless\Wordpress\Pagination;

class Page
{
    private int $number;
    private array $items;

    public function __construct(int $page_number, array $items)
    {
        $this->number = $page_number;
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
