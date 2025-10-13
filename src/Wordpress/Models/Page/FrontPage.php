<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Page;

use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Wordpress\Models\Page;
use Wordless\Wordpress\Models\Page\FrontPage\Exceptions\FailedToSetFrontPage;
use Wordless\Wordpress\Models\Page\FrontPage\Exceptions\FrontPageIsNotSet;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use WP_Post;

class FrontPage extends Page
{
    final public const OPTION_KEY_FRONT_PAGE_ID = 'page_on_front';
    final public const OPTION_KEY_SHOW_ON_FRONT = 'show_on_front';

    /**
     * @param int|WP_Post|Page $page
     * @param bool $override
     * @return static
     * @throws FailedToSetFrontPage
     */
    public static function setPageAsFrontPage(
        int|WP_Post|Page $page,
        bool             $override = false
    ): static
    {
        $page_id = is_int($page) ? $page : $page->ID;

        try {
            $frontPage = new static;

            if (!$override) {
                return $frontPage;
            }

            if ($frontPage->ID === $page_id) {
                return $frontPage;
            }
        } catch (FrontPageIsNotSet) {
        }

        try {
            Option::createUpdateOrFail(self::OPTION_KEY_FRONT_PAGE_ID, $page_id);
            Option::createUpdateOrFail(self::OPTION_KEY_SHOW_ON_FRONT, 'page');

            return new static;
        } catch (FailedToUpdateOption|InitializingModelWithWrongPostType|PostTypeNotRegistered $exception) {
            throw new FailedToSetFrontPage($page, $override, $exception);
        }
    }

    /**
     * @throws FrontPageIsNotSet
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function __construct()
    {
        if (!empty($front_page_id = Option::get(self::OPTION_KEY_FRONT_PAGE_ID))
            && is_numeric($front_page_id)
            && !is_null($frontPage = get_post((int)$front_page_id))) {
            parent::__construct($frontPage);

            return;
        }

        throw new FrontPageIsNotSet;
    }
}
