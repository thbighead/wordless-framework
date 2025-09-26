<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Pagination\Pages\Traits;

use Wordless\Application\Libraries\Pagination\Pages;
use Wordless\Application\Libraries\Pagination\Pages\Page;

trait Rotate
{
    public function goToPage(int $index): Page
    {
        if (!is_null($newPage = parent::goToPage($index))) {
            return $newPage;
        }

        return $this->goToPage($index > 0 ? $index - $this->number_of_pages : $index + $this->number_of_pages);
    }

    /**
     * @return Page
     */
    public function nextPage(): Page
    {
        return parent::nextPage();
    }

    /**
     * @return Page
     */
    public function previousPage(): Page
    {
        return parent::previousPage();
    }
}
