<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Pagination\Pages;

readonly class Page
{
    public int $position;

    public function __construct(public int $index, public array $items)
    {
        $this->position = $this->index + 1;
    }
}
