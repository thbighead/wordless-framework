<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedUsers;

trait Pagination
{
    private const KEY_COUNT_TOTAL = 'count_total';

    /**
     * @param int $users_per_page
     * @param int $page
     * @param array|null $fields
     * @param array $extra_arguments
     * @return PaginatedUsers
     * @throws EmptyQueryBuilderArguments
     */
    public function paginate(
        int $users_per_page,
        int $page = 1,
        ?array $fields = null,
        array $extra_arguments = []
    ): PaginatedUsers
    {
        $page_index = ($page = max($page, 1)) - 1;
        $users_per_page = max($users_per_page, 1);

        return new PaginatedUsers(
            $this->preparePagination($page, $fields, $extra_arguments),
            $users_per_page,
            $page_index
        );
    }

    public function paginateRotating()
    {

    }

    public function preparePagination(
        int $page = 1,
        ?array $fields = null,
        array $extra_arguments = []
    ): static
    {
        $this->arguments[self::KEY_COUNT_TOTAL] = true;
        $this->arguments['paged'] = $page;

        if (!empty($fields)) {
            $this->arguments[self::KEY_FIELDS] = $fields;
        }

        return $this->resolveExtraArguments($this->arguments, $extra_arguments);
    }
}
