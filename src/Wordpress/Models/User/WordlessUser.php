<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User;

use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\Models\User;
use Wordless\Wordpress\Models\User\WordlessUser\Exceptions\TryingToDeleteWordlessUser;
use Wordless\Wordpress\Models\User\WordlessUser\Exceptions\TryingToUpdateWordlessUser;

final class WordlessUser extends User
{
    public const EMAIL = 'wordless@wordless.wordless';

    private static WordlessUser $wordlessUser;

    public static function create(): WordlessUser
    {
        if (self::find() === null) {
            return parent::create(self::EMAIL, Str::random());
        }

        return self::make();
    }

    public static function find(): ?WordlessUser
    {
        return self::findByEmail(self::EMAIL);
    }

    public static function make(): self
    {
        return self::$wordlessUser ?? self::$wordlessUser = new self;
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
     * @return void
     * @throws TryingToUpdateWordlessUser
     */
    public function save(): void
    {
        throw new TryingToUpdateWordlessUser;
    }

    private function __construct()
    {
        parent::__construct(get_user_by('email', self::EMAIL), false);
    }
}
