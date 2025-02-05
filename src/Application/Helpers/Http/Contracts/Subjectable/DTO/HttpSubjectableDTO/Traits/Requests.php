<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Application\Helpers\Http\Contracts\Subjectable\DTO\HttpSubjectableDTO\Traits;

use JsonException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Helpers\Http;
use Wordless\Application\Helpers\Http\Exceptions\RequestFailed;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Http\Request\Enums\Verb;
use Wordless\Infrastructure\Http\Response;

trait Requests
{
    /**
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $additional_headers
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws PathException
     * @throws RequestFailed
     */
    public function delete(
        string       $endpoint,
        array|string $body = [],
        array        $additional_headers = []
    ): Response
    {
        return $this->request(Verb::delete, $endpoint, $body, $additional_headers);
    }

    /**
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $additional_headers
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws PathException
     * @throws RequestFailed
     */
    public function get(
        string       $endpoint,
        array|string $body = [],
        array        $additional_headers = []
    ): Response
    {
        return $this->request(Verb::get, $endpoint, $body, $additional_headers);
    }

    /**
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $additional_headers
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws PathException
     * @throws RequestFailed
     */
    public function patch(
        string       $endpoint,
        array|string $body = [],
        array        $additional_headers = []
    ): Response
    {
        return $this->request(Verb::patch, $endpoint, $body, $additional_headers);
    }

    /**
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $additional_headers
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws PathException
     * @throws RequestFailed
     */
    public function post(
        string       $endpoint,
        array|string $body = [],
        array        $additional_headers = []
    ): Response
    {
        return $this->request(Verb::post, $endpoint, $body, $additional_headers);
    }

    /**
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $additional_headers
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws PathException
     * @throws RequestFailed
     */
    public function put(
        string       $endpoint,
        array|string $body = [],
        array        $additional_headers = []
    ): Response
    {
        return $this->request(Verb::put, $endpoint, $body, $additional_headers);
    }

    /**
     * @param Verb $httpVerb
     * @param string $endpoint
     * @param array<string, string>|string $body
     * @param array<string, string> $additional_headers
     * @return Response
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws JsonException
     * @throws PathException
     * @throws RequestFailed
     */
    public function request(
        Verb         $httpVerb,
        string       $endpoint,
        array|string $body = [],
        array        $additional_headers = []
    ): Response
    {
        return Http::request(
            $httpVerb,
            $endpoint,
            $body,
            $this->default_headers + $additional_headers,
            $this->only_with_ssl,
            $this->version
        );
    }
}
