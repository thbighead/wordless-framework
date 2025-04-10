<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Application\Helpers\GetType;
use Wordless\Application\Libraries\PolymorphicConstructor\Contracts\IPolymorphicConstructor;
use Wordless\Application\Libraries\PolymorphicConstructor\Traits\PolymorphicConstructorGuesser;

class MetaData implements IPolymorphicConstructor
{
    use PolymorphicConstructorGuesser;

    readonly public string $key;
    private mixed $value;

    public static function constructorsDictionary(): array
    {
        return [
            1 => [GetType::ARRAY => '__constructFromWpArray'],
            2 => [
                GetType::STRING . GetType::STRING => '__constructFromValues',
                GetType::STRING . GetType::BOOLEAN => '__constructFromValues',
                GetType::STRING . GetType::INTEGER => '__constructFromValues',
                GetType::STRING . GetType::DOUBLE => '__constructFromValues',
                GetType::STRING . GetType::ARRAY => '__constructFromValues',
                GetType::STRING . GetType::OBJECT => '__constructFromValues',
                GetType::STRING . GetType::NULL => '__constructFromValues',
            ],
        ];
    }

    public function __constructFromWpArray(array $wp_meta_data_array): void
    {
        $this->key = $wp_meta_data_array['key'] ?? null;
        $this->value = $wp_meta_data_array['value'] ?? null;
    }

    public function __constructFromValues(string $meta_key, mixed $meta_value): void
    {
        $this->key = $meta_key;
        $this->value = $meta_value;
    }
}
