<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User;

use Wordless\Application\Helpers\Str;
use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors;
use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Create\Exceptions\FailedToCreateUser;
use Wordless\Wordpress\Models\User\Traits\TempUser;
use Wordless\Wordpress\Models\User\WordlessUser\Exceptions\TryingToDeleteWordlessUser;
use Wordless\Wordpress\Models\User\WordlessUser\Exceptions\TryingToUpdateWordlessUser;

final class WordlessUser extends User
{
    use Constructors;
    use TempUser;

    public const EMAIL = 'wordless@wordless.wordless';

    private static WordlessUser $wordlessUser;

    /**
     * @param string $email
     * @param string $password
     * @param string|null $username
     * @return static
     * @throws FailedToCreateUser
     * @noinspection PhpUnnecessaryStaticReferenceInspection
     */
    public static function create(string $email = '', string $password = '', ?string $username = null): static
    {
        if (self::find() === null) {
            return parent::create(self::EMAIL, Str::random());
        }

        return self::make();
    }

    public static function find(): ?self
    {
        return self::findByEmail(self::EMAIL);
    }

    public static function findByEmail(string $user_email = ''): null
    {
        return null;
    }

    public static function findById(int $user_id = 0): null
    {
        return null;
    }

    public static function findBySlug(string $user_slug = ''): null
    {
        return null;
    }

    public static function findByUsername(string $username = ''): null
    {
        return null;
    }

    public static function make(): self
    {
        return self::getInstance();
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

    private function __construct()
    {
        parent::__construct(get_user_by('email', self::EMAIL), false);
    }
}
