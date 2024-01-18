<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Type\Traits\MimeType;

trait Type
{
    use MimeType;

    public function onlyOfType(StandardType|PostType|string $type, StandardType|PostType|string ...$types): static
    {
        $this->arguments[PostType::QUERY_TYPE_KEY] = [];

        return $this->whereType(...Arr::prepend($types, $type));
    }

    /**
     * @param StandardType|PostType|string $type
     * @param StandardType|PostType|string ...$types
     * @return $this
     */
    public function whereType(StandardType|PostType|string $type, StandardType|PostType|string ...$types): static
    {
        if (!isset($this->arguments[PostType::QUERY_TYPE_KEY]) || $this->isWhereTypeAny()) {
            $this->arguments[PostType::QUERY_TYPE_KEY] = [];
        }

        foreach (Arr::prepend($types, $type) as $type) {
            if ($this->isTypeAny($type = $this->retrieveTypeAsString($type))) {
                $this->arguments[PostType::QUERY_TYPE_KEY] = StandardType::ANY;

                return $this;
            }

            $this->arguments[PostType::QUERY_TYPE_KEY][] = $this->retrieveTypeAsString($type);
        }

        return $this;
    }

    private function isTypeAny(StandardType|PostType|string $type): bool
    {
        return Str::lower($this->retrieveTypeAsString($type)) === StandardType::ANY;
    }

    private function isTypeAttachment(StandardType|PostType|string $type): bool
    {
        if ($type instanceof StandardType) {
            return $type === StandardType::attachment;
        }

        if ($type instanceof PostType) {
            return $type->is(StandardType::attachment);
        }

        return Str::lower($type) === StandardType::attachment->name;
    }

    private function isWhereTypeAny(): bool
    {
        return ($this->arguments[PostType::QUERY_TYPE_KEY] ?? '') === StandardType::ANY;
    }

    private function isWhereTypePage(): bool
    {
        if (count($where_type = $this->arguments[PostType::QUERY_TYPE_KEY] ?? []) !== 1) {
            return false;
        }

        return Arr::first($where_type) === StandardType::page->name;
    }

    private function isWhereTypeIncluding(StandardType|PostType $type): bool
    {
        if ($this->isWhereTypeAny()) {
            return true;
        }

        $where_type = $this->arguments[PostType::QUERY_TYPE_KEY] ?? null;

        return is_array($where_type) && Arr::hasValue($where_type, $type->name);
    }

    private function isWhereTypeIncludingAttachment(): bool
    {
        return $this->isWhereTypeIncluding(StandardType::attachment);
    }

    private function isWhereTypeIncludingPage(): bool
    {
        return $this->isWhereTypeIncluding(StandardType::page);
    }

    private function isWhereTypeIncludingAnyPost(): bool
    {
        if ($this->isWhereTypeAny()) {
            return true;
        }

        $where_type = $this->arguments[PostType::QUERY_TYPE_KEY] ?? null;

        return is_array($where_type) && Arr::hasAnyOtherValueThan($where_type, StandardType::page->name);
    }

    private function retrieveTypeAsString(StandardType|PostType|string $type): string
    {
        return is_string($type) ? $type : $type->name;
    }
}
