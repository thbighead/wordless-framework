<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
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
        if (!isset($this->arguments[self::KEY_POST_STATUS]) || $this->isWhereStatusAny()) {
            $this->arguments[self::KEY_POST_STATUS] = [];
        }

        foreach (Arr::prepend($statuses, $status) as $status) {
            if (!is_string($status)) {
                $status = $this->extractStatusString($status);
            }

            if ($this->isStatusAny($status)) {
                $this->arguments[self::KEY_POST_STATUS] = StandardStatus::ANY;

                return $this;
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

    private function isStatusAny(StandardStatus|PostStatus|string $status): bool
    {
        return $this->extractStatusString($status) === StandardStatus::ANY;
    }

    private function isWhereStatusAny(): bool
    {
        return !is_null($this->arguments[self::KEY_POST_STATUS][StandardStatus::ANY] ?? null);
    }

    private function isWhereStatusIncluding(StandardStatus|PostStatus|string $status): bool
    {
        if (!is_string($status)) {
            $status = $this->extractStatusString($status);
        }

        if ($this->isStatusAny($status)) {
            return true;
        }

        return ($this->arguments[self::KEY_POST_STATUS][$status] ?? null) === $status;
    }

    private function isWhereStatusIncludingPublish(): bool
    {
        return $this->isWhereStatusIncluding(StandardStatus::publish);
    }
}
