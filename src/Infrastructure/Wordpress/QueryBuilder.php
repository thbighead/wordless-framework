<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use WP_Query;

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
