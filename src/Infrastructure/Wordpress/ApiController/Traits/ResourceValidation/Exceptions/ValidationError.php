<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\ApiController\Traits\ResourceValidation\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Http\Response\Enums\StatusCode;
use Wordless\Infrastructure\Wordpress\ApiController\Response;

class ValidationError extends InvalidArgumentException
{
    private Response $response;

    /**
     * @param array<string, string[]> $violations
     * @param Throwable|null $previous
     */
    public function __construct(public readonly array $violations, ?Throwable $previous = null)
    {
        parent::__construct(
            __('Invalid parameters.'),
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }

    /**
     * @return Response
     * @throws InvalidArgumentException
     */
    public function getResponse(): Response
    {
        return $this->response ?? $this->response = $this->mountResponse();
    }

    /**
     * @return Response
     * @throws InvalidArgumentException
     */
    private function mountResponse(): Response
    {
        return Response::error(
            StatusCode::unprocessable_entity_422,
            $this->getMessage(),
            ['errors' => $this->violations]
        );
    }
}
