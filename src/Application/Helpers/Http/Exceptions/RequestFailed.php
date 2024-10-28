<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Http\Exceptions;

use Throwable;
use Wordless\Exceptions\WpErrorException;
use WP_Error;

class RequestFailed extends WpErrorException
{
    public function __construct(public readonly WP_Error $requestError, ?Throwable $previous = null)
    {
        parent::__construct($this->requestError, $previous);
    }
}
