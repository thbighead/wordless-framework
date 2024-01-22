<?php declare(strict_types=1);

namespace Wordless\Wordpress;

use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors;
use Wordless\Wordpress\Models\Page;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use WP_Post;

final class FrontPage
{
    use Constructors;

    private const FRONT_PAGE_ID_OPTION_KEY = 'page_on_front';

    private Page $frontPage;

    public function getPage(): ?Page
    {
        return $this->frontPage ?? null;
    }

    /**
     * @param int|WP_Post|Page $page
     * @param bool $override
     * @return void
     * @throws FailedToUpdateOption
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function setPageAsFrontPage(int|WP_Post|Page $page, bool $override = false): void
    {
        if (!$override && isset($this->frontPage)) {
            return;
        }

        if (!($page instanceof Page)) {
            $page = new Page($page);
        }

        if (isset($this->frontPage) && $this->frontPage->ID === $page->ID) {
            return;
        }

        Option::updateOrFail(self::FRONT_PAGE_ID_OPTION_KEY, $page->ID);
        Option::updateOrFail('show_on_front', 'page');

        $this->frontPage = $page;
    }

    /**
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    protected function __construct()
    {
        if (!is_null($front_page_id = Option::get(self::FRONT_PAGE_ID_OPTION_KEY))) {
            $this->frontPage = new Page($front_page_id);
        }
    }
}
