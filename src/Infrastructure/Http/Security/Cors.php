<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http\Security;

use Fruitcake\Cors\CorsService;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Application\Listeners\HandleCors;
use Wordless\Infrastructure\Http\Request;
use Wordless\Infrastructure\Http\Request\Enums\Verb;
use Wordless\Infrastructure\Http\Response\Enums\StatusCode;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use Wordless\Wordpress\Models\User\Exceptions\NoUserAuthenticated;

final class Cors extends Singleton
{
    public const CONFIG_KEY = 'cors';
    private const HEADER_ACCESS_CONTROL_REQUEST_METHOD = 'Access-Control-Request-Method';

    private Request $request;
    private CorsService $service;

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public static function enable(): void
    {
        self::getInstance()->handleCorsRequest();
    }

    /**
     * @throws EmptyConfigKey
     * @throws InvalidAcfFunction
     * @throws NoUserAuthenticated
     * @throws PathNotFoundException
     */
    protected function __construct()
    {
        $this->service = new CorsService(Config::wordlessCors()->get());
        $this->request = Request::getInstance();

        parent::__construct();
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    private function handleCorsRequest(): void
    {
        if ($this->isPreflightRequest()) {
            $this->handlePreflightRequest();
        }

        if ($this->isOriginRequestNotAllowed()) {
            $this->handleOriginRequestNotAllowed();
        }

        HandleCors::hookIt(function () {
            $fakeResponse = new Response;

            if ($this->request->isMethodVerb(Verb::options)) {
                $this->service->varyHeader($fakeResponse, self::HEADER_ACCESS_CONTROL_REQUEST_METHOD);
            }

            $this->service->addActualRequestHeaders($fakeResponse, $this->request)->sendHeaders();
        });
    }

    private function handlePreflightRequest(): void
    {
        $this->service->varyHeader(
            $this->service->handlePreflightRequest($this->request),
            self::HEADER_ACCESS_CONTROL_REQUEST_METHOD
        )->send();

        die;
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    private function handleOriginRequestNotAllowed(): void
    {
        $this->service->addActualRequestHeaders(
            new Response('CORS: Origin not allowed.', StatusCode::forbidden_403->value),
            $this->request
        )->send();

        die;
    }

    private function isOriginRequestNotAllowed(): bool
    {
        return $this->service->isCorsRequest($this->request) && !$this->service->isOriginAllowed($this->request);
    }

    private function isPreflightRequest(): bool
    {
        return $this->service->isPreflightRequest($this->request);
    }
}
