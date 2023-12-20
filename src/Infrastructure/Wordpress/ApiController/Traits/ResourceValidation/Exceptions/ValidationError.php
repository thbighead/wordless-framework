<?php

namespace Wordless\Infrastructure\Wordpress\ApiController\Traits\ResourceValidation\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\ApiController\Response;

class ValidationError extends InvalidArgumentException
{
    private Response $response;

    /**
     * @param array<string, string[]> $violations
     * @param Throwable|null $previous
     */
    public function __construct(private readonly array $violations, ?Throwable $previous = null)
    {
        parent::__construct(
            __('Invalid parameters.'),
            ExceptionCode::intentional_interrupt->value,
            $previous
        );

        $this->mountResponse();
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function mountResponse(): void
    {
        $this->response = Response::error(
            Response::HTTP_422_UNPROCESSABLE_ENTITY,
            $this->getMessage(),
            ['errors' => $this->violations]
        );
    }
}
