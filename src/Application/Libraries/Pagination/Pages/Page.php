<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Pagination\Pages;

use Wordless\Application\Libraries\Pagination\Pages\Page\Exceptions\EmptyPage;

readonly class Page
{
    public int $position;

    /**
     * @param int $index
     * @param array $items
     * @throws EmptyPage
     */
    public function __construct(public int $index, public array $items)
    {
        if ((empty($this->items))) {
            throw new EmptyPage($this->index);
        }

        $this->position = $this->index + 1;
    }
}
