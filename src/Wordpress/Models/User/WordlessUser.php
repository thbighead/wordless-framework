<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Enums\DefaultRole;
use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Create\Exceptions\FailedToCreateUser;
use Wordless\Wordpress\Models\User\WordlessUser\Exceptions\TryingToDeleteWordlessUser;
use Wordless\Wordpress\Models\User\WordlessUser\Exceptions\TryingToUpdateWordlessUser;

final class WordlessUser extends User
{
    use Constructors;

    public const USERNAME = 'wordless';

    public static function email(): string
    {
        try {
            $app_host = Environment::get('APP_HOST', $app_host = 'wordless.wordless');
        } catch (FormatException|DotEnvNotSetException) {
        }

        return self::USERNAME . "@$app_host";
    }

    /**
     * @param string $email
     * @param string $password
     * @param string|null $username
     * @param Role|DefaultRole|string|null $role
     * @return static
     * @throws FailedToCreateUser
     * @noinspection PhpUnnecessaryStaticReferenceInspection
     */
    public static function create(
        string                  $email = '',
        string                  $password = '',
        ?string                 $username = null,
        Role|DefaultRole|string|null $role = DefaultRole::subscriber
    ): static
    {
        if (self::find() === null) {
            return parent::create(self::email(), self::password(), role: DefaultRole::admin);
        }

        return self::make();
    }

    public static function find(): ?self
    {
        return parent::findByEmail(self::email());
    }

    public static function make(): self
    {
        return self::getInstance();
    }

    final public static function password(): string
    {
        return Str::random();
    }

    /**
     * @return void
     * @throws TryingToDeleteWordlessUser
     */
    public function delete(): void
    {
        throw new TryingToDeleteWordlessUser;
    }

    /**
     * @return $this
     * @throws TryingToUpdateWordlessUser
     * @noinspection PhpUnnecessaryStaticReferenceInspection
     */
    public function save(): static
    {
        throw new TryingToUpdateWordlessUser;
    }

    protected function __construct()
    {
        parent::__construct(get_user_by('email', self::email()), false);
    }
}
