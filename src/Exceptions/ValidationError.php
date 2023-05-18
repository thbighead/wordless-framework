<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Infrastructure\Http\Response;

class ValidationError extends Exception
{
    /** @var array<string, string[]> $violations */
    private array $violations;
    private Response $response;

    /**
     * @param array<string, string[]> $violations
     * @param Throwable|null $previous
     */
    public function __construct(array $violations, Throwable $previous = null)
    {
        $this->violations = $violations;

        parent::__construct(
            __('Invalid parameters.'),
            Response::HTTP_422_UNPROCESSABLE_ENTITY,
            $previous
        );

        $this->mountResponse();
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    public function mountResponse()
    {
        $this->response = Response::error(
            $this->getCode(),
            $this->getMessage(),
            ['errors' => $this->violations]
        );
    }
}
