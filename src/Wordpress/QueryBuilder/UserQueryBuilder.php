<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder\WpQueryBuilder;
use Wordless\Wordpress\QueryBuilder\Traits\HasMetaSubQuery;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Capability;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\OrderBy;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Permission;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Role;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Search;
use WP_User;
use WP_User_Query;

class UserQueryBuilder extends WpQueryBuilder
{
    use Capability;
    use HasMetaSubQuery;
    use OrderBy;
    use Permission;
    use Role;
    use Search;

    public static function make(): static
    {
        return new static;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function except(WP_User|int $user, WP_User|int ...$users): static
    {
        $this->arguments['exclude'] = array_map(function (WP_User|int $user) {
            return $user->ID ?? $user;
        }, Arr::prepend($users, $user));

        return $this;
    }

    /**
     * @return WP_User_Query
     */
    protected function getQuery(): WP_User_Query
    {
        return parent::getQuery();
    }

    protected function mountNewWpQuery(): WP_User_Query
    {
        return new WP_User_Query;
    }
}
