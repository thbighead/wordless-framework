<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Enums\ObjectType;

trait ArgumentsBuilder
{
    /**
     * @return array<string, string|int|bool|array>
     */
    protected function buildArguments(): array
    {
        try {
            $arguments = parent::buildArguments();
        } catch (EmptyQueryBuilderArguments) {
            $arguments = [];
        }

        $this->buildObjectTypeArgument($arguments);

        return $arguments;
    }

    private function buildObjectTypeArgument(array &$arguments): static
    {
        if (isset($arguments[self::ARGUMENT_KEY_OBJECT_TYPE])) {
            $object_types = [];

            foreach ($arguments[self::ARGUMENT_KEY_OBJECT_TYPE] as $objectType) {
                /** @var ObjectType $objectType */
                $object_types[$objectType->name] = $objectType->name;
            }

            $arguments[self::ARGUMENT_KEY_OBJECT_TYPE] = array_values($object_types);
        }

        return $this;
    }
}
