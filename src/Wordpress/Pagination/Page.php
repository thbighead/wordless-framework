<?php declare(strict_types=1);

namespace Wordless\Wordpress\Pagination;

use WP_Post;

class Page
{
    private int $number;
    /** @var WP_Post[] $items */
    private array $items;

    public function __construct(int $page_number, array $items)
    {
        $this->number = $page_number;
        $this->items = $items;
    }

    /**
     * @return WP_Post[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
