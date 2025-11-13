<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\PostStatus\Exceptions;

use RuntimeException;
use stdClass;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\PostStatus;

class FailedToConstructPostStatus extends RuntimeException
{
    public function __construct(
        public readonly ?string   $post_status_slug,
        public readonly ?stdClass $postStatusObject,
        ?Throwable                $previous = null
    )
    {
        parent::__construct($this->mountMessage(), ExceptionCode::development_error->value, $previous);
    }

    private function mountMessage(): string
    {
        $message = 'Couldn\'t instantiate a '
            . PostStatus::class
            . ' object';

        if ($this->post_status_slug !== null) {
            $message .= " for post status with slug $this->post_status_slug";
        }

        return "$message.";
    }
}
