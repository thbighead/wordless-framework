<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\DesignPattern\Singleton\Traits;

use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors\Exceptions\TryingToUnserializeSingleton;

trait Constructors
{
    private static array $instances = [];

    public static function getInstance(): static
    {
        return self::$instances[static::class] ?? self::$instances[static::class] = new static;
    }

    /**
     * @return mixed
     * @throws TryingToUnserializeSingleton
     */
    final public function __wakeup()
    {
        throw new TryingToUnserializeSingleton(static::class);
    }

    protected function __construct()
    {
    }

    private function __clone()
    {
    }
}
