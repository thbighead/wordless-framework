<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User;

use Random\RandomException;
use Wordless\Application\Commands\WordlessInstall;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Enums\StandardRole;
use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Create\Exceptions\FailedToCreateUser;
use Wordless\Wordpress\Models\User\WordlessUser\Exceptions\TryingToDeleteWordlessUser;
use Wordless\Wordpress\Models\User\WordlessUser\Exceptions\TryingToUpdateWordlessUser;

class WordlessUser extends User
{
    use Constructors;

    final public const USERNAME = WordlessInstall::WORDLESS_ADMIN_USER;

    final public static function email(): string
    {
        try {
            $app_host = Environment::get('APP_HOST', $app_host = 'wordless.wordless');
        } catch (CannotResolveEnvironmentGet) {
        }

        return self::USERNAME . "@$app_host";
    }

    /**
     * @param string $email
     * @param string $password
     * @param string|null $username
     * @param Role|StandardRole|string|null $role
     * @return static
     * @throws FailedToCreateUser
     * @throws RandomException
     */
    final public static function create(
        string                       $email = '',
        string                       $password = '',
        ?string                      $username = null,
        Role|StandardRole|string|null $role = StandardRole::subscriber
    ): static
    {
        if (self::find() === null) {
            return parent::create(self::email(), self::password(), role: StandardRole::admin);
        }

        return self::make();
    }

    final public static function find(): ?self
    {
        return parent::findByEmail(self::email());
    }

    final public static function make(): self
    {
        return self::getInstance();
    }

    /**
     * @return string
     * @throws RandomException
     */
    private static function password(): string
    {
        return Str::random();
    }

    /**
     * @return void
     * @throws TryingToDeleteWordlessUser
     */
    final public function delete(): void
    {
        throw new TryingToDeleteWordlessUser;
    }

    /**
     * @return $this
     * @throws TryingToUpdateWordlessUser
     */
    final public function save(): static
    {
        throw new TryingToUpdateWordlessUser;
    }

    final protected function __construct()
    {
        parent::__construct(get_user_by('email', self::email()));
    }
}
