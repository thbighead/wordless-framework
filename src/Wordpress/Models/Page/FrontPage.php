<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Page;

use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Wordpress\Models\Page;
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
     * @param bool $load_acfs
     * @param bool $override
     * @return static
     * @throws FailedToUpdateOption
     * @throws FrontPageIsNotSet
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public static function setPageAsFrontPage(
        int|WP_Post|Page $page,
        bool $load_acfs = true,
        bool $override = false
    ): static
    {
        if (!($page instanceof Page)) {
            $page = new Page($page);
        }

        try {
            $frontPage = new FrontPage($load_acfs);

            if (!$override) {
                return $frontPage;
            }

            if ($frontPage->ID === $page->ID) {
                return $frontPage;
            }
        } catch (FrontPageIsNotSet) {
        } finally {
            Option::updateOrFail(self::OPTION_KEY_FRONT_PAGE_ID, $page->ID);
            Option::updateOrFail(self::OPTION_KEY_SHOW_ON_FRONT, 'page');

            return new FrontPage($load_acfs);
        }
    }

    /**
     * @param bool $with_acfs
     * @throws FrontPageIsNotSet
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    protected function __construct(bool $with_acfs = true)
    {
        if (!is_null($front_page_id = Option::get(self::OPTION_KEY_FRONT_PAGE_ID))
            && is_numeric($front_page_id)) {
            parent::__construct((int)$front_page_id, $with_acfs);
        }

        throw new FrontPageIsNotSet;
    }
}
