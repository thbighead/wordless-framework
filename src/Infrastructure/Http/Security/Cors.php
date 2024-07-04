<?php

namespace Wordless\Infrastructure\Http\Security;

use Fruitcake\Cors\CorsService;
use Symfony\Component\HttpFoundation\Response;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Infrastructure\Http\Request;

final class Cors extends Singleton
{
    public const CONFIG_KEY = 'cors';

    private Request $request;
    private CorsService $service;

    public static function enable(): void
    {
        $singleton = self::getInstance();

        if ($singleton->isCorsRequest()) {
            $singleton->handleCorsRequest();
        }
    }

    /**
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    private function __construct()
    {
        $this->service = new CorsService(Config::wordlessCors()->get());
        $this->request = Request::getInstance();

        parent::__construct();
    }

    private function handleCorsRequest(): void
    {
        if ($this->service->isPreflightRequest($this->request)) {
            $this->handlePreflightRequest();
        }

        $this->service->addActualRequestHeaders(new Response, $this->request)->sendHeaders();
    }

    private function handlePreflightRequest(): void
    {
        $this->service->handlePreflightRequest($this->request)->send();
        die;
    }

    private function isCorsRequest(): bool
    {
        return $this->service->isCorsRequest($this->request);
    }
}
