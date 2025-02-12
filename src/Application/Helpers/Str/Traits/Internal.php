<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Traits;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use InvalidArgumentException;
use Wordless\Application\Helpers\Str\Enums\Language;

trait Internal
{
    /** @var Inflector[] $inflectors */
    private static array $inflectors = [];

    /**
     * @param Language|null $language
     * @return Inflector
     * @throws InvalidArgumentException
     */
    private static function getInflector(?Language $language = null): Inflector
    {
        return self::$inflectors[$language?->name] ?? self::$inflectors[$language?->name] = $language === null ?
            InflectorFactory::create()->build() :
            InflectorFactory::createForLanguage($language->value)->build();
    }
}
