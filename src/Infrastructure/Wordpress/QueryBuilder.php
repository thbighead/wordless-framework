<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;

abstract class QueryBuilder
{
    protected array $arguments = [];

    /**
     * @return array<string, string|int|bool|array>
     * @throws EmptyQueryBuilderArguments
     */
    protected function buildArguments(): array
    {
        return $this->validateArguments($this->arguments);
    }

    final protected function resolveExtraArguments(array &$arguments, array $extra_arguments): static
    {
        foreach ($extra_arguments as $extra_argument_key => $extra_argument_value) {
            $arguments[$extra_argument_key] = $extra_argument_value;
        }

        return $this;
    }

    /**
     * @param array $arguments
     * @return array
     * @throws EmptyQueryBuilderArguments
     */
    private function validateArguments(array $arguments): array
    {
        if (empty($arguments)) {
            throw new EmptyQueryBuilderArguments(static::class);
        }

        return $arguments;
    }
}
