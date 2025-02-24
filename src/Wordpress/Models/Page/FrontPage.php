<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Page;

use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Wordpress\Models\Page;
use Wordless\Wordpress\Models\Page\FrontPage\Exceptions\FrontPageIsNotSet;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
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
     * @throws InvalidAcfFunction
     * @throws PostTypeNotRegistered
     */
    public static function setPageAsFrontPage(
        int|WP_Post|Page $page,
        bool $load_acfs = true,
        bool $override = false
    ): static
    {
        if (is_int($page)) {
            $page = new Page($page, false);
        }

        try {
            $frontPage = new static($load_acfs);

            if (!$override) {
                return $frontPage;
            }

            if ($frontPage->ID === $page->ID) {
                return $frontPage;
            }
        } catch (FrontPageIsNotSet) {
        }

        Option::createUpdateOrFail(self::OPTION_KEY_FRONT_PAGE_ID, $page->ID);
        Option::createUpdateOrFail(self::OPTION_KEY_SHOW_ON_FRONT, 'page');

        return new static($load_acfs);
    }

    /**
     * @param bool $with_acfs
     * @throws FrontPageIsNotSet
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidAcfFunction
     * @throws PostTypeNotRegistered
     */
    public function __construct(bool $with_acfs = true)
    {
        if (!empty($front_page_id = Option::get(self::OPTION_KEY_FRONT_PAGE_ID))
            && is_numeric($front_page_id)
            && !is_null($frontPage = get_post((int)$front_page_id))) {
            parent::__construct($frontPage, $with_acfs);

            return;
        }

        throw new FrontPageIsNotSet;
    }
}
