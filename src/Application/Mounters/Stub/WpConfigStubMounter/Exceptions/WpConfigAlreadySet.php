<?php

namespace Wordless\Application\Mounters\Stub\WpConfigStubMounter\Exceptions;

use Exception;
use Throwable;
use Wordless\Enums\ExceptionCode;

class WpConfigAlreadySet extends Exception
{
    public function __construct(private readonly string $existing_wp_config_filepath, ?Throwable $previous = null)
    {
        parent::__construct(
            "A possibly correct config file at $existing_wp_config_filepath already exists.",
            ExceptionCode::caught_internally->value,
            $previous
        );
    }

    public function getExistingWpConfigFilepath(): string
    {
        return $this->existing_wp_config_filepath;
    }
}
