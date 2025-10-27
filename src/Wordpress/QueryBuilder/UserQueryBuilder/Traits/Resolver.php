<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver\Enums\ReturnField;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver\Traits\ArgumentsFixer;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver\Traits\Pagination;
use WP_User;

trait Resolver
{
    use ArgumentsFixer;
    use Pagination;

    private const KEY_FIELDS = 'fields';

    private bool $already_queried = false;

    /**
     * @param array $extra_arguments
     * @param bool $query_again
     * @return int
     * @throws EmptyQueryBuilderArguments
     */
    public function count(array $extra_arguments = [], bool $query_again = false): int
    {
        if (!$this->already_queried || $query_again || !empty($extra_arguments)) {
            $this->arguments[self::KEY_COUNT_TOTAL] = true;

            $this->query($this->buildArguments($extra_arguments));
        }

        return ($this->arguments[self::KEY_COUNT_TOTAL] ?? false)
            ? $this->getQuery()->get_total()
            : count($this->getQuery()->get_results());
    }

    /**
     * @param int $quantity
     * @param ReturnField[]|null $fields
     * @param array $extra_arguments
     * @return WP_User|WP_User[]
     * @throws EmptyQueryBuilderArguments
     */
    public function first(int $quantity = 1, ?array $fields = null, array $extra_arguments = []): WP_User|array
    {
        return Arr::first(
            $this->limit($quantity = max($quantity, 1))
                ->get($fields, $extra_arguments),
            $quantity
        );
    }

    /**
     * @param ReturnField[]|null $fields
     * @param array $extra_arguments
     * @return WP_User[]
     * @throws EmptyQueryBuilderArguments
     */
    public function get(?array $fields = null, array $extra_arguments = []): array
    {
        if (!empty($fields)) {
            $this->select(...$fields);
        }

        return $this->query($this->buildArguments($extra_arguments))->getQuery()->get_results();
    }

    public function select(ReturnField $field, ReturnField ...$fields): static
    {
        $this->arguments[self::KEY_FIELDS] = Arr::prepend($fields, $field);

        return $this;
    }

    /**
     * @param array $extra_arguments
     * @return array<string, string|int|bool|array>
     * @throws EmptyQueryBuilderArguments
     */
    protected function buildArguments(array $extra_arguments = []): array
    {
        $this->fixArguments();

        $arguments = parent::buildArguments();

        $this->resolveExtraArguments($arguments, $extra_arguments);

        return $arguments;
    }

    private function query(array $arguments): static
    {
        $this->getQuery()->prepare_query($arguments);
        $this->getQuery()->query();

        $this->already_queried = true;

        return $this;
    }
}
