<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Application\Helpers;

use JsonException;
use Ramsey\Uuid\Uuid;
use Random\RandomException;
use Wordless\Application\Helpers\Str\Contracts\Subjectable;
use Wordless\Application\Helpers\Str\Enums\Encoding;
use Wordless\Application\Helpers\Str\Enums\UuidVersion;
use Wordless\Application\Helpers\Str\Traits\Boolean;
use Wordless\Application\Helpers\Str\Traits\Internal;
use Wordless\Application\Helpers\Str\Traits\Mutators;
use Wordless\Application\Helpers\Str\Traits\Substring;

class Str extends Subjectable
{
    use Boolean;
    use Internal;
    use Mutators;
    use Substring;

    final public const DEFAULT_RANDOM_SIZE = 16;

    /**
     * @param string $json
     * @return array
     * @throws JsonException
     */
    public static function jsonDecode(string $json): array
    {
        return json_decode($json, true, flags: JSON_THROW_ON_ERROR);
    }

    public static function length(string $string, ?Encoding $encoding = null): int
    {
        return mb_strlen($string, $encoding?->value);
    }

    /**
     * @param int $size
     * @return string
     * @throws RandomException
     */
    public static function random(int $size = self::DEFAULT_RANDOM_SIZE): string
    {
        if ($size <= 0) {
            $size = self::DEFAULT_RANDOM_SIZE;
        }

        if (function_exists('wp_generate_password')) {
            return wp_generate_password($size, false);
        }

        $keyspace = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;

        for ($i = 0; $i < $size; ++$i) {
            $pieces [] = $keyspace[random_int(0, $max)];
        }

        return implode('', $pieces);
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
