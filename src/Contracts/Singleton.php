<?php

namespace Wordless\Contracts;

use Wordless\Contracts\Singleton\Exceptions\TryingToUnserializeSingleton;

trait Singleton
{
    private static array $instances = [];

    public static function getInstance()
    {
        return self::$instances[static::class] ?? self::$instances[static::class] = new static;
    }

    protected function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @throws TryingToUnserializeSingleton
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function __wakeup()
    {
        throw new TryingToUnserializeSingleton(static::class);
    }
}
