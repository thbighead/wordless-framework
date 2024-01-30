<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Doctrine\Inflector\Language;
use InvalidArgumentException;
use JsonException;
use Ramsey\Uuid\Uuid;
use Wordless\Application\Helpers\Str\Contracts\Subjectable;
use Wordless\Application\Helpers\Str\Enums\UuidVersion;
use Wordless\Application\Helpers\Str\Traits\Boolean;
use Wordless\Application\Helpers\Str\Traits\Internal;
use Wordless\Application\Helpers\Str\Traits\Mutators;
use Wordless\Application\Helpers\Str\Traits\Substring;
use Wordless\Application\Helpers\Str\Traits\WordCase;

class Str extends Subjectable
{
    use Boolean;
    use Internal;
    use Mutators;
    use Substring;
    use WordCase;

    final public const DEFAULT_RANDOM_SIZE = 16;

    /**
     * @param string $json
     * @return array
     * @throws JsonException
     */
    public static function jsonDecode(string $json): array
    {
        return json_decode($json, true, flags:  JSON_THROW_ON_ERROR);
    }

    /**
     * @param string $string
     * @param string $language
     * @return string
     * @throws InvalidArgumentException
     */
    public static function plural(string $string, string $language = Language::ENGLISH): string
    {
        return self::getInflector($language)->pluralize($string);
    }

    public static function random(int $size = self::DEFAULT_RANDOM_SIZE): string
    {
        return wp_generate_password($size <= 0 ? self::DEFAULT_RANDOM_SIZE : $size, false);
    }

    /**
     * @param string $string
     * @param string $language
     * @return string
     * @throws InvalidArgumentException
     */
    public static function singular(string $string, string $language = Language::ENGLISH): string
    {
        return self::getInflector($language)->singularize($string);
    }

    /**
     * @param UuidVersion $version
     * @param bool $with_dashes
     * @return string
     */
    public static function uuid(UuidVersion $version = UuidVersion::four, bool $with_dashes = true): string
    {
        $uuid = match ($version) {
            UuidVersion::one => Uuid::uuid1(),
            UuidVersion::two => Uuid::uuid2(Uuid::DCE_DOMAIN_PERSON),
            UuidVersion::three => Uuid::uuid3(Uuid::NAMESPACE_DNS, php_uname('n')),
            UuidVersion::four => Uuid::uuid4(),
            UuidVersion::five => Uuid::uuid5(Uuid::NAMESPACE_DNS, php_uname('n')),
            UuidVersion::six => Uuid::uuid6(),
        };

        return $with_dashes ? $uuid->toString() : static::replace($uuid->toString(), '-', '');
    }
}
