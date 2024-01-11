<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

abstract class QueryBuilder
{
    abstract public function first(): mixed;

    abstract public function get(): array;

    protected array $arguments = [];

    /**
     * @return array<string, string|int|bool|array>
     */
    protected function buildArguments(): array
    {
        return $this->arguments;
    }
}
