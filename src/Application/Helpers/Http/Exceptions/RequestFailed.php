<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Http\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Error;

class RequestFailed extends ErrorException
{
    public readonly array $all_errors_data;
    public readonly array $error_messages;

    public function __construct(public readonly WP_Error $requestError, ?Throwable $previous = null)
    {
        $this->error_messages = $this->requestError->get_error_messages();
        $this->all_errors_data = $this->mountAllErrorsData();

        parent::__construct(
            $this->getErrorMessagesAsString(),
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }

    public function getErrorMessagesAsString(): string
    {
        return implode('. ', $this->error_messages);
    }

    private function mountAllErrorsData(): array
    {
        $all_errors_data = [];

        foreach ($this->requestError->get_error_codes() as $error_code) {
            $all_errors_data[$error_code] = $this->requestError->get_all_error_data($error_code);
        }

        return $all_errors_data;
    }
}
