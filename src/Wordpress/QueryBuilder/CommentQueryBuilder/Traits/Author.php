<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;

trait Author
{
    public function includeEvenIfUnapprovedAuthorEmails(string $email, string ...$emails): static
    {
        $emails = Arr::prepend($emails, $email);

        $this->arguments[self::KEY_INCLUDE_UNAPPROVED] = isset($this->arguments[self::KEY_INCLUDE_UNAPPROVED])
            ? array_merge($this->arguments[self::KEY_INCLUDE_UNAPPROVED], $emails)
            : $emails;

        return $this;
    }

    public function whereAuthorEmail(string $email): static
    {
        $this->arguments['author_email'] = $email;

        return $this;
    }

    public function whereAuthorId(int $author_id): static
    {
        return $this->whereAuthorIdIn($author_id);
    }

    public function whereAuthorIdIn(int $author_id, int ...$author_ids): static
    {
        $this->arguments['author__in'] = Arr::prepend($author_ids, $author_id);

        return $this;
    }

    public function whereAuthorIdNotIn(int $author_id, int ...$author_ids): static
    {
        $this->arguments['author__not_in'] = Arr::prepend($author_ids, $author_id);

        return $this;
    }

    public function whereAuthorUrl(string $url): static
    {
        $this->arguments['author_url'] = $url;

        return $this;
    }
}
