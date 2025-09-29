<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder\WpQueryBuilder;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\QueryBuilder\Traits\HasMetaSubQuery;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Capability;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Login;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Nicename;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\OrderBy;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Permission;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Role;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Search;
use WP_Post_Type;
use WP_User;
use WP_User_Query;

class UserQueryBuilder extends WpQueryBuilder
{
    use Capability;
    use HasMetaSubQuery;
    use Login;
    use Nicename;
    use OrderBy;
    use Permission;
    use Resolver;
    use Role;
    use Search;

    private const KEY_NUMBER = 'number';

    public static function make(?bool $enable_cache = null): static
    {
        return new static($enable_cache);
    }

    public function __construct(?bool $enable_cache = null)
    {
        parent::__construct();

        $this->arguments[self::KEY_COUNT_TOTAL] = false;

        if ($enable_cache === false) {
            $this->arguments['cache_results'] = false;
        }
    }

    public function except(WP_User|int $user, WP_User|int ...$users): static
    {
        $this->arguments['exclude'] = array_map(function (WP_User|int $user) {
            return $user->ID ?? $user;
        }, Arr::prepend($users, $user));

        return $this;
    }

    public function hasPublishedAny(
        PostType|WP_Post_Type|StandardType|null $type = null,
        PostType|WP_Post_Type|StandardType      ...$types
    ): static
    {
        $this->arguments['has_published_posts'] = $type === null ? true : array_map(
            function (PostType|WP_Post_Type|StandardType $type) {
                return $type->name;
            },
            Arr::prepend($types, $type)
        );

        return $this;
    }

    public function limit(int $quantity): static
    {
        $this->arguments[self::KEY_NUMBER] = max($quantity, 1);

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
