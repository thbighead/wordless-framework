<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Pagination\Pages\Traits;

use Wordless\Application\Libraries\Pagination\Pages\Page;
use Wordless\Application\Libraries\Pagination\Pages\Page\Exceptions\EmptyPage;

trait Rotate
{
    /**
     * @param int $index
     * @return Page
     * @throws EmptyPage
     */
    public function goToPage(int $index): Page
    {
        if (!is_null($newPage = parent::goToPage($index))) {
            return $newPage;
        }

        return $this->goToPage($index > 0 ? $index - $this->number_of_pages : $index + $this->number_of_pages);
    }

    /**
     * @return Page
     * @throws EmptyPage
     */
    public function nextPage(): Page
    {
        return parent::nextPage();
    }

    /**
     * @return Page
     * @throws EmptyPage
     */
    public function previousPage(): Page
    {
        return parent::previousPage();
    }
}
