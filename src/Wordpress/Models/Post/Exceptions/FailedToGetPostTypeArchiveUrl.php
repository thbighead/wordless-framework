<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGetPostTypeArchiveUrl extends DomainException
{
    public function __construct(
        public readonly string $modelClassNamespace,
        public readonly string $post_type_key,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Failed to get an archive URL of post type $this->post_type_key for model $this->modelClassNamespace. Does it really is configured via WordPress to have an archive page?",
            ExceptionCode::logic_control->value,
            $previous
        );
    }
}
