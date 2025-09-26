<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;

trait Nicename
{
    public function whereNicename(string $nicename): static
    {
        $this->arguments['nicename'] = $nicename;

        return $this;
    }

    public function whereNicenameIn(string $nicename, string ...$nicenames): static
    {
        $this->arguments['nicename__in'] = Arr::prepend($nicenames, $nicename);

        return $this;
    }

    public function whereNicenameNotIn(string $nicename, string ...$nicenames): static
    {
        $this->arguments['nicename__not_in'] = Arr::prepend($nicenames, $nicename);

        return $this;
    }
}
