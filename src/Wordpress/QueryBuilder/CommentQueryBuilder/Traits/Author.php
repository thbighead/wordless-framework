<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\Models\User;
use WP_User;

trait Author
{
    private const KEY_AUTHOR_IN = 'author__in';
    private const KEY_AUTHOR_NOT_IN = 'author__not_in';
    private const KEY_USER_ID = 'user_id';

    public function includeEvenIfUnapprovedAuthorEmails(string $email, string ...$emails): static
    {
        $emails = Arr::prepend($emails, $email);

        $this->arguments[self::KEY_INCLUDE_UNAPPROVED] = isset($this->arguments[self::KEY_INCLUDE_UNAPPROVED])
            ? array_merge($this->arguments[self::KEY_INCLUDE_UNAPPROVED], $emails)
            : $emails;

        return $this;
    }

    public function onlyRegisteredAuthors(): static
    {
        return $this->whereAuthorIdNotIn(0);
    }

    public function onlyUnregisteredAuthors(): static
    {
        return $this->whereAuthorId(0);
    }

    public function whereAuthor(User|WP_User|int $author): static
    {
        return $this->whereAuthorId($author->ID ?? $author);
    }

    public function whereAuthorIn(User|WP_User|int $author, User|WP_User|int ...$authors): static
    {
        return $this->whereAuthorIdIn(
            $author->ID ?? $author,
            ...array_map(function (User|WP_User|int $author): int {
                return $author->ID ?? $author;
            }, $authors)
        );
    }

    public function whereAuthorNotIn(User|WP_User|int $author, User|WP_User|int ...$authors): static
    {
        return $this->whereAuthorIdNotIn(
            $author->ID ?? $author,
            ...array_map(function (User|WP_User|int $author): int {
                return $author->ID ?? $author;
            }, $authors)
        );
    }

    public function whereAuthorEmail(string $email): static
    {
        $this->arguments['author_email'] = $email;

        return $this;
    }

    public function whereAuthorId(int $author_id): static
    {
        $this->arguments[self::KEY_USER_ID] = $author_id;

        unset($this->arguments[self::KEY_AUTHOR_IN], $this->arguments[self::KEY_AUTHOR_NOT_IN]);

        return $this;
    }

    public function whereAuthorIdIn(int $author_id, int ...$author_ids): static
    {
        $this->arguments[self::KEY_AUTHOR_IN] = Arr::prepend($author_ids, $author_id);

        unset($this->arguments[self::KEY_USER_ID], $this->arguments[self::KEY_AUTHOR_NOT_IN]);

        return $this;
    }

    public function whereAuthorIdNotIn(int $author_id, int ...$author_ids): static
    {
        $this->arguments[self::KEY_AUTHOR_NOT_IN] = Arr::prepend($author_ids, $author_id);

        unset($this->arguments[self::KEY_USER_ID], $this->arguments[self::KEY_AUTHOR_IN]);

        return $this;
    }

    public function whereAuthorUrl(string $url): static
    {
        $this->arguments['author_url'] = $url;

        return $this;
    }
}
