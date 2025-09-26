<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;

trait Login
{
    public function whereLogin(string $login): static
    {
        $this->arguments['login'] = $login;

        return $this;
    }

    public function whereLoginIn(string $login, string ...$logins): static
    {
        $this->arguments['login__in'] = Arr::prepend($logins, $login);

        return $this;
    }

    public function whereLoginNotIn(string $login, string ...$logins): static
    {
        $this->arguments['login__not_in'] = Arr::prepend($logins, $login);

        return $this;
    }
}
