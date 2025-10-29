<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors;
use Wordless\Infrastructure\Http\Request\Enums\Verb;
use Wordless\Wordpress\Models\User\Exceptions\NoUserAuthenticated;

class Request extends SymfonyRequest
{
    use Constructors;

    protected static function newInstance(): static
    {
        return new self(
            $_GET,
            $_POST,
            cookies: $_COOKIE,
            files: $_FILES,
            server: $_SERVER
        );
    }

    /** @noinspection PhpRedundantMethodOverrideInspection */
    public function __clone()
    {
        parent::__clone();
    }

    public function isMethodVerb(Verb $method): bool
    {
        return parent::isMethod($method->value);
    }

    /**
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param $content
     */
    protected function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
              $content = null
    )
    {
        try {
            parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        } catch (NoUserAuthenticated) {
        }
    }
}
