<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver\Traits;

use Wordless\Application\Libraries\Pagination\Pages\Page\Exceptions\EmptyPage;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver\Enums\ReturnField;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedUsers;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedUsers\Rotating;

trait Pagination
{
    private const KEY_COUNT_TOTAL = 'count_total';
    private const KEY_PAGED = 'paged';

    public function isPaginating(): bool
    {
        return isset($this->arguments[self::KEY_PAGED]);
    }

    /**
     * @param int $users_per_page
     * @param ReturnField[]|null $fields
     * @param array $extra_arguments
     * @return PaginatedUsers
     * @throws EmptyPage
     * @throws EmptyQueryBuilderArguments
     */
    public function paginate(
        int    $users_per_page,
        ?array $fields = null,
        array  $extra_arguments = []
    ): PaginatedUsers
    {
        $users_per_page = max($users_per_page, 1);

        if (!empty($fields)) {
            $this->select(...$fields);
        }

        return new PaginatedUsers(
            $this->resolveExtraArguments($this->arguments, $extra_arguments),
            $users_per_page
        );
    }

    /**
     * @param int $users_per_page
     * @param ReturnField[]|null $fields
     * @param array $extra_arguments
     * @return Rotating
     * @throws EmptyPage
     * @throws EmptyQueryBuilderArguments
     */
    public function paginateRotating(
        int    $users_per_page,
        ?array $fields = null,
        array  $extra_arguments = []
    ): Rotating
    {
        $users_per_page = max($users_per_page, 1);

        if (!empty($fields)) {
            $this->select(...$fields);
        }

        return new Rotating(
            $this->resolveExtraArguments($this->arguments, $extra_arguments),
            $users_per_page
        );
    }

    public function preparePagination(int $users_per_page, int $page = 1): static
    {
        $this->arguments[self::KEY_COUNT_TOTAL] = true;
        $this->arguments[self::KEY_PAGED] = $page;

        return $this->limit(max($users_per_page, 1));
    }
}
