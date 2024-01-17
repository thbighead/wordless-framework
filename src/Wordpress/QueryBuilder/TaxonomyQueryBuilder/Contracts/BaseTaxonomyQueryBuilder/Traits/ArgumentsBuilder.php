<?php

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Traits;

use Wordless\Wordpress\Enums\ObjectType;

trait ArgumentsBuilder
{
    protected function buildArguments(): array
    {
        $arguments = parent::buildArguments();

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
