<?php

namespace Wordless\Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Wordless\Exceptions\HttpRequestFailed;
use Wordless\Helpers\Environment;
use Wordless\Helpers\Http;
use Wordless\Tests\WordlessTestCase;

class HomeRedirectTest extends WordlessTestCase
{
    /**
     * @return void
     * @throws HttpRequestFailed
     */
    public function testAvoidRedirectionsWhenEquals()
    {
        $response = Http::get(Environment::get('APP_URL'));
        $response_code = $response['response']['code'] ?? null;

        if (Environment::get('APP_URL') === Environment::get('FRONT_END_URL')) {
            $this->assertEquals(Response::HTTP_OK, $response_code);
        } else {
            $this->assertEquals(Response::HTTP_PERMANENTLY_REDIRECT, $response_code);
        }
    }
}