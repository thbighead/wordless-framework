<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Traits;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use InvalidArgumentException;

trait Internal
{
    /** @var Inflector[] $inflectors */
    private static array $inflectors = [];

    /**
     * @param string|null $language
     * @return Inflector
     * @throws InvalidArgumentException
     */
    private static function getInflector(?string $language = null): Inflector
    {
        return self::$inflectors[$language] ?? self::$inflectors[$language] = $language === null ?
            InflectorFactory::create()->build() :
            InflectorFactory::createForLanguage($language)->build();
    }
}
