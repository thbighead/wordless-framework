<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Wordpress\Models\Post\Enums\StandardStatus;
use Wordless\Wordpress\Models\PostStatus;

trait Status
{
    private const KEY_POST_STATUS = 'post_status';

    public function whereStatus(
        StandardStatus|PostStatus|string $status,
        StandardStatus|PostStatus|string ...$statuses
    ): static
    {
        array_unshift($statuses, $status);

        if (!isset($this->arguments[self::KEY_POST_STATUS])) {
            $this->arguments[self::KEY_POST_STATUS] = [];
        }

        foreach ($statuses as $status) {
            if (!is_string($status)) {
                $status = $this->extractStatusString($status);
            }

            $this->arguments[self::KEY_POST_STATUS][$status] = $status;
        }

        return $this;
    }

    private function extractStatusString(StandardStatus|PostStatus $status): string
    {
        return match (true) {
            $status instanceof PostStatus => $status->name,
            $status instanceof StandardStatus => $status->value,
        };
    }

    private function isForStatus(StandardStatus|PostStatus|string $status): bool
    {
        if (!is_string($status)) {
            $status = $this->extractStatusString($status);
        }

        return ($this->arguments[self::KEY_POST_STATUS][$status] ?? null) === $status;
    }

    private function isForStatusAny(): bool
    {
        return $this->isForStatus(StandardStatus::ANY);
    }
}
