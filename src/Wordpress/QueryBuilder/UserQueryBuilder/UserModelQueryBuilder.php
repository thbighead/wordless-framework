<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder;

use Wordless\Application\Helpers\Database;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\Models\User\Exceptions\NoUserAuthenticated;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Delete\Exceptions\FailedToDeleteUser;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Update\Exceptions\FailedToUpdateUser;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidMethodException;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\UserModelQueryBuilder\Exceptions\FailedToUpdateUsers;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\UserModelQueryBuilder\Exceptions\UpdateAnonymousFunctionDidNotReturnUserObject;
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

    /**
     * @return User[]
     * @throws EmptyQueryBuilderArguments
     * @throws FailedToDeleteUser
     */
    public function delete(): array
    {
        /** @var User[] $users */
        $users = $this->get();

        foreach ($users as $user) {
            $user->delete();
        }

        return $users;
    }

    public function toUserQueryBuilder(): UserQueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @param callable $item_changes
     * @return User[]
     * @throws FailedToUpdateUsers
     * @throws UpdateAnonymousFunctionDidNotReturnUserObject
     */
    public function update(callable $item_changes): array
    {
        try {
            /** @var User[] $users */
            $users = $this->get();

            Database::smartTransaction(function () use ($users, $item_changes) {
                foreach ($users as $user) {
                    $changedUser = $item_changes($user);

                    if ($changedUser instanceof User) {
                        $changedUser->save();
                        continue;
                    }

                    throw new UpdateAnonymousFunctionDidNotReturnUserObject;
                }
            });

            return $users;
        } catch (EmptyQueryBuilderArguments|FailedToUpdateUser|QueryError $exception) {
            throw new FailedToUpdateUsers($exception);
        } catch (UpdateAnonymousFunctionDidNotReturnUserObject $exception) {
            throw $exception;
        }
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
