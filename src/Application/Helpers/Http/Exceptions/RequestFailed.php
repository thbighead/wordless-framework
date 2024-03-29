<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Http\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Error;

class RequestFailed extends ErrorException
{
    public function __construct(private readonly WP_Error $requestError, ?Throwable $previous = null)
    {
        $errors = [
            'messages' => $this->requestError->get_error_messages(),
            'response' => $this->requestError->get_all_error_data(),
        ];

        parent::__construct(
            var_export($errors, true),
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }

    public function getRequestError(): WP_Error
    {
        return $this->requestError;
    }
}
