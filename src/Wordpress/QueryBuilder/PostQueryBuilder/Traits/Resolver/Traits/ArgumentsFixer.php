<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits;

use Wordless\Wordpress\Models\Post\Enums\StandardStatus;

trait ArgumentsFixer
{
    private function fixArguments(): void
    {
        $this->fixPostStatusArgumentBasedOnPostTypeArgument()
            ->fixPostIdArgumentsBasedOnPostTypeArgument()
            ->fixPostSlugArgumentsBasedOnPostTypeArgument()
            ->fixSearchArgument()
            ->fixPostStatusArgument();
    }

    private function fixPostIdArgumentsBasedOnPostTypeArgument(): static
    {
        if ($this->isWhereTypePage()) {
            unset($this->arguments[self::KEY_POST_ID]);

            return $this;
        }

        if (!$this->isWhereTypeIncludingPage()) {
            unset($this->arguments[self::KEY_PAGE_ID]);
        }

        return $this;
    }

    private function fixPostSlugArgumentsBasedOnPostTypeArgument(): static
    {
        if ($this->isWhereTypePage()) {
            unset($this->arguments[self::KEY_POST_SLUG]);

            return $this;
        }

        if (!$this->isWhereTypeIncludingPage()) {
            unset($this->arguments[self::KEY_PAGE_SLUG]);
        }

        return $this;
    }

    private function fixPostStatusArgument(): void
    {
        if (isset($this->arguments[self::KEY_POST_STATUS])) {
            $this->arguments[self::KEY_POST_STATUS] = array_values($this->arguments[self::KEY_POST_STATUS]);
        }
    }

    private function fixPostStatusArgumentBasedOnPostTypeArgument(): static
    {
        if ($this->isWhereStatusReallyAny()) {
            return $this;
        }

        if ($this->isWhereTypeIncludingAttachment() && $this->isWhereStatusIncludingPublish()) {
            $this->whereStatus(StandardStatus::inherit);
        }

        return $this;
    }

    private function fixSearchArgument(): static
    {
        if (!isset($this->arguments[self::KEY_SEARCH])) {
            return $this;
        }

        $unfixed_search_argument = $this->arguments[self::KEY_SEARCH];
        $this->arguments[self::KEY_SEARCH] = [];

        foreach ($unfixed_search_argument as $word => $is_included) {
            $this->arguments[self::KEY_SEARCH][] = $is_included ? $word : "-$word";
        }

        $this->arguments[self::KEY_SEARCH] = implode(' ', $this->arguments[self::KEY_SEARCH]);

        return $this;
    }
}
