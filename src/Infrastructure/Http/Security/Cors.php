<?php

namespace Wordless\Infrastructure\Http\Security;

use Wordless\Application\Libraries\DesignPattern\Singleton;

final class Cors extends Singleton
{
    public const CONFIG_KEY = 'cors';

    public static function enable(): void
    {
        self::getInstance();
    }

    private function __construct()
    {
        parent::__construct();
    }
}
