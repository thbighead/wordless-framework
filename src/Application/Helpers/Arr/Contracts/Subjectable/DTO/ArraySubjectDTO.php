<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO;

use JsonException;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO\Traits\Internal;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

final class ArraySubjectDTO extends SubjectDTO
{
    use Internal;

    public function __toString(): string
    {
        return $this->print();
    }

    public function append(mixed $value, string|int|null $with_key = null): self
    {
        $this->subject = Arr::append($this->subject, $value, $with_key);

        return $this->incrementSize()->recalculateAssociativeAfterAddition();
    }

    public function except(array $except_keys): self
    {
        $this->subject = Arr::except($this->subject, $except_keys);

        return $this->updateSize()->recalculateAssociative();
    }

    public function first(int $quantity = 1): mixed
    {
        return Arr::first($this->subject, $quantity);
    }

    /**
     * @param int|string $key
     * @param mixed|null $default
     * @return mixed
     * @throws FailedToParseArrayKey
     */
    public function get(int|string $key, mixed $default = null): mixed
    {
        return Arr::get($this->subject, $key, $default);
    }

    public function getFirstKey(): string|int|null
    {
        return Arr::getFirstKey($this->subject);
    }

    public function getIndexOfKey(string|int|null $key): ?int
    {
        return Arr::getIndexOfKey($this->subject, $key);
    }

    /**
     * @param int|string $key
     * @return mixed
     * @throws FailedToParseArrayKey
     * @throws FailedToFindArrayKey
     */
    public function getOrFail(int|string $key): mixed
    {
        return Arr::getOrFail($this->subject, $key);
    }

    public function hasAnyOtherValueThan(mixed $forbidden_value): bool
    {
        return Arr::hasAnyOtherValueThan($this->subject, $forbidden_value);
    }

    public function hasKey(string|int|null $key): bool
    {
        return Arr::hasKey($this->subject, $key);
    }

    public function hasValue(mixed $value): bool
    {
        return Arr::hasValue($this->subject, $value);
    }

    public function isAssociative(): bool
    {
        return $this->associative ?? $this->associative = Arr::isAssociative($this->subject);
    }

    public function isEmpty(): bool
    {
        return isset($this->size) ? $this->size === 0 : Arr::isEmpty($this->subject);
    }

    public function lastIndex(): int
    {
        return isset($this->size) ? $this->size - 1 : Arr::lastIndex($this->subject);
    }

    /**
     * @param array $only_keys
     * @return $this
     * @throws FailedToParseArrayKey
     */
    public function only(array $only_keys): self
    {
        $this->subject = Arr::only($this->subject, $only_keys);

        return $this->updateSize()->recalculateAssociative();
    }

    public function prepend(mixed $value, string|int|null $with_key = null): self
    {
        $this->subject = Arr::prepend($this->subject, $value, $with_key);

        return $this->incrementSize()->recalculateAssociativeAfterAddition();
    }

    public function packBy(int $by): self
    {
        $this->subject = Arr::packBy($this->subject, $by);

        return $this->resetAssociative()->updateSize();
    }

    public function print(): string
    {
        return rtrim(var_export($this->subject, true));
    }

    public function pushValueIntoIndex(int $index, mixed $value, string|int|null $with_key = null): self
    {
        $this->subject = Arr::pushValueIntoIndex($this->subject, $index, $value, $with_key);

        return $this->incrementSize()->recalculateAssociativeAfterAddition();
    }

    public function recursiveJoin(array $array, array ...$arrays): self
    {
        $this->subject = Arr::recursiveJoin($this->subject, $array, ...$arrays);

        return $this->updateSize()->recalculateAssociative();
    }

    public function searchValueKey(mixed $value): int|string|null
    {
        return Arr::searchValueKey($this->subject, $value);
    }

    public function size(): int
    {
        return $this->size ?? $this->size = Arr::size($this->subject);
    }

    /**
     * @return string
     * @throws JsonException
     */
    public function toJson(): string
    {
        return Arr::toJson($this->subject);
    }

    /**
     * @param int|string|null $key
     * @return mixed
     * @throws FailedToParseArrayKey
     */
    public function unwrap(int|string|null $key = null): mixed
    {
        return Arr::unwrap($this->subject, $key);
    }
}
