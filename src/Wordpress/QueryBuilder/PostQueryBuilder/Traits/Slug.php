<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;

trait Slug
{
    private const KEY_PAGE_SLUG = 'pagename';
    private const KEY_POST_SLUG = 'name';
    private const KEY_POST_SLUG_IN = 'post_name__in';

    /**
     * @param string $slug
     * @param string ...$slugs
     * @return $this
     */
    public function whereSlug(string $slug, string ...$slugs): static
    {
        if (empty($slugs)) {
            $this->arguments[self::KEY_PAGE_SLUG] = $this->arguments[self::KEY_POST_SLUG] = $slug;

            unset($this->arguments[self::KEY_POST_SLUG_IN]);

            return $this;
        }

        $this->arguments[self::KEY_POST_SLUG_IN] = Arr::prepend($slugs, $slug);

        unset($this->arguments[self::KEY_PAGE_SLUG]);
        unset($this->arguments[self::KEY_POST_SLUG]);

        return $this;
    }
}
