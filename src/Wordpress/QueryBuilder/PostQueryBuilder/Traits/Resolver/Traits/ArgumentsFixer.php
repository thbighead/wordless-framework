<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits;

use Wordless\Wordpress\Models\Post\Enums\StandardStatus;

trait ArgumentsFixer
{
    private function fixArguments(): array
    {
        $this->fixPostStatusArgumentBasedOnPostTypeArgument()
            ->fixPostIdArgumentsBasedOnPostTypeArgument()
            ->fixPostSlugArgumentsBasedOnPostTypeArgument()
            ->fixPostStatusArgument();

        return $this->arguments;
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
        if ($this->isWhereStatusAny()) {
            return $this;
        }

        if ($this->isWhereTypeIncludingAttachment() && $this->isWhereStatusIncludingPublish()) {
            $this->whereStatus(StandardStatus::inherit);
        }

        return $this;
    }
}
