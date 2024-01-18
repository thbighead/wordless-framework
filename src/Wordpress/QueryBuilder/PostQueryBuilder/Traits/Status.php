<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\Models\Post\Enums\StandardStatus;
use Wordless\Wordpress\Models\PostStatus;

trait Status
{
    private const KEY_POST_STATUS = 'post_status';

    public function onlyWithStatus(
        StandardStatus|PostStatus|string $status,
        StandardStatus|PostStatus|string ...$statuses
    ): static
    {
        $this->arguments[self::KEY_POST_STATUS] = [];

        return $this->whereStatus(...Arr::prepend($statuses, $status));
    }

    public function whereAnyStatus(): static
    {
        $this->arguments[self::KEY_POST_STATUS] = [StandardStatus::reallyAny() => StandardStatus::reallyAny()];

        return $this;
    }

    public function whereStatus(
        StandardStatus|PostStatus|string $status,
        StandardStatus|PostStatus|string ...$statuses
    ): static
    {
        if (!isset($this->arguments[self::KEY_POST_STATUS]) || $this->isWhereStatusReallyAny()) {
            $this->arguments[self::KEY_POST_STATUS] = [];
        }

        foreach (Arr::prepend($statuses, $status) as $status) {
            if ($this->isStatusReallyAny($status)) {
                return $this->whereAnyStatus();
            }

            $status_string = !is_string($status) ? $this->extractStatusString($status) : $status;

            $this->arguments[self::KEY_POST_STATUS][$status_string] = $status;
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
        return (is_string($status) ? $status : $this->extractStatusString($status)) === StandardStatus::ANY;
    }

    private function isStatusReallyAny(StandardStatus|PostStatus|string $status): bool
    {
        return $status === StandardStatus::reallyAny();
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

        if ($this->isStatusReallyAny($status)) {
            return true;
        }

        return ($this->arguments[self::KEY_POST_STATUS][$status] ?? null) === $status;
    }

    private function isWhereStatusIncludingPublish(): bool
    {
        return $this->isWhereStatusIncluding(StandardStatus::publish);
    }

    private function isWhereStatusReallyAny(): bool
    {
        if (!is_null($this->arguments[self::KEY_POST_STATUS][StandardStatus::reallyAny()] ?? null)) {
            return true;
        }

        foreach (StandardStatus::REALLY_ANY as $status_string) {
            if (is_null($this->arguments[self::KEY_POST_STATUS][$status_string] ?? null)) {
                return false;
            }
        }

        return true;
    }
}
