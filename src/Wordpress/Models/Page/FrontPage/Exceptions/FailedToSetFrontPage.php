<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Page\FrontPage\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\Page;
use WP_Post;

class FailedToSetFrontPage extends RuntimeException
{
    public function __construct(
        public readonly int|WP_Post|Page $page,
        public readonly bool             $override = false,
        ?Throwable                       $previous = null
    )
    {
        $page_id = is_int($page) ? $page : $page->ID;
        $override_text = $this->override ? 'on' : 'off';

        parent::__construct(
            "Could not set page of ID $page_id as front page with override mode $override_text",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
