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
            foreach ($arguments[self::ARGUMENT_KEY_OBJECT_TYPE] as &$objectType) {
                /** @var ObjectType $objectType */
                $objectType = $objectType->name;
            }
        }

        return $this;
    }
}
