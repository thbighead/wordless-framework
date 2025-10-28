<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\Models\Comment\Enums\StandardType;

trait Type
{
    private const KEY_TYPE = 'type';
    private const KEY_TYPE_IN = 'type__in';
    private const KEY_TYPE_NOT_IN = 'type__not_in';

    public function whereType(StandardType|string $type): static
    {
        $this->arguments[self::KEY_TYPE] = $type->value ?? $type;

        unset($this->arguments[self::KEY_TYPE_IN], $this->arguments[self::KEY_TYPE_NOT_IN]);

        return $this;
    }

    public function whereTypeIn(StandardType|string $type, StandardType|string ...$types): static
    {
        foreach (Arr::prepend($types, $type) as $comment_type) {
            if ($comment_type instanceof StandardType) {
                $comment_type = $comment_type->value;
            }

            $this->arguments[self::KEY_TYPE_IN][] = $comment_type;
        }

        unset($this->arguments[self::KEY_TYPE], $this->arguments[self::KEY_TYPE_NOT_IN]);

        return $this;
    }

    public function whereTypeNotIn(StandardType|string $type, StandardType|string ...$types): static
    {
        foreach (Arr::prepend($types, $type) as $comment_type) {
            if ($comment_type instanceof StandardType) {
                $comment_type = $comment_type->value;
            }

            $this->arguments[self::KEY_TYPE_NOT_IN][] = $comment_type;
        }

        unset($this->arguments[self::KEY_TYPE], $this->arguments[self::KEY_TYPE_IN]);

        return $this;
    }
}
