<?php declare(strict_types=1);

namespace Wordless\Application\Commands\ReplaceBaseUrls\Exceptions;

use Throwable;
use Wordless\Core\Exceptions\DotEnvNotSetException as BaseDotEnvNotSetException;

class DotEnvNotSetException extends BaseDotEnvNotSetException
{
    public function __construct(private readonly string $app_url_env_variable_name, ?Throwable $previous = null)
    {
        parent::__construct(
            ".env \"$this->app_url_env_variable_name\" variable returned a non expected value.",
            $previous
        );
    }
}
