<?php

namespace Wordless\Wordpress;

use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Infrastructure\Wordpress\CustomPost\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Page;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use WP_Post;

final class FrontPage
{
    use Singleton;

    private const FRONT_PAGE_ID_OPTION_KEY = 'page_on_front';

    private Page $frontPage;

    /**
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    protected function __construct()
    {
        if (!is_null($front_page_id = Option::get('page_on_front'))) {
            $this->frontPage = new Page($front_page_id);
        }
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

        Option::update('page_on_front', $page->ID);
        Option::update('show_on_front', 'page');

        $this->frontPage = $page;
    }
}
