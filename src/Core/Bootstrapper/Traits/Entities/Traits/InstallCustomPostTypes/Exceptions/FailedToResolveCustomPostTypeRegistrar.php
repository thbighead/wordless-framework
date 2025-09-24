<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Entities\Traits\InstallCustomPostTypes\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\CustomPost;

class FailedToResolveCustomPostTypeRegistrar extends RuntimeException
{
    public function __construct(
        public readonly string|CustomPost $custom_post_type_namespace,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Failed registering $this->custom_post_type_namespace.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
