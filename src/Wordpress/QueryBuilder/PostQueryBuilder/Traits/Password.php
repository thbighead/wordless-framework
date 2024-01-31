<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

trait Password
{
    private const KEY_HAS_PASSWORD = 'has_password';
    private const KEY_POST_PASSWORD = 'post_password';

    public function deactivatePasswordCheck(): static
    {
        unset($this->arguments[self::KEY_HAS_PASSWORD]);
        unset($this->arguments[self::KEY_POST_PASSWORD]);

        return $this;
    }

    public function onlyWithoutPassword(): static
    {
        $this->arguments[self::KEY_HAS_PASSWORD] = false;

        unset($this->arguments[self::KEY_POST_PASSWORD]);

        return $this;
    }

    public function onlyWithPassword(?string $password = null): static
    {
        $this->arguments[self::KEY_HAS_PASSWORD] = true;

        if ($password !== null) {
            $this->arguments[self::KEY_POST_PASSWORD] = $password;
        }

        return $this;
    }
}
