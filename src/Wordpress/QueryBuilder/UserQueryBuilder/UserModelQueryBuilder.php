<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder;

use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\Models\User\Exceptions\NoUserAuthenticated;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidMethodException;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder;
use WP_User;

/**
 * @mixin UserQueryBuilder
 */
class UserModelQueryBuilder
{
    private UserQueryBuilder $queryBuilder;

    public static function make(): static
    {
        return new static;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return $this|array|bool|int|string|User|null
     * @throws InvalidMethodException
     */
    public function __call(string $name, array $arguments)
    {
        if (is_callable([$this->queryBuilder, $name])) {
            $result = $this->queryBuilder->$name(...$arguments);

            return $this->resolveCallResult($result);
        }

        throw new InvalidMethodException($name, static::class);
    }

    public function __construct()
    {
        $this->queryBuilder = UserQueryBuilder::make();
    }

    public function toUserQueryBuilder(): UserQueryBuilder
    {
        return $this->queryBuilder;
    }

    private function resolveCallResult(
        bool|int|string|array|WP_User|UserQueryBuilder|null $result
    ): bool|int|string|array|User|static|null
    {
        if ($result instanceof UserQueryBuilder) {
            return $this;
        }

        if ($result instanceof WP_User) {
            try {
                return User::make($result);
            } catch (NoUserAuthenticated) {
                return null;
            }
        }

        if (is_array($result)) {
            return $this->resolveCallArrayResult($result);
        }

        return $result;
    }

    /**
     * @param array $result
     * @return User[]
     */
    private function resolveCallArrayResult(array $result): array
    {
        $resolved_result = [];

        foreach ($result as $key => $item) {
            if (!($item instanceof WP_User)) {
                return $result;
            }

            try {
                $resolved_result[$key] = User::make($item);
            } catch (NoUserAuthenticated) {
                return $result;
            }
        }

        return $resolved_result;
    }
}
