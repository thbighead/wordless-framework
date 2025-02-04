<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO;

use JsonException;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO\Traits\Internal;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;

final class ArraySubjectDTO extends SubjectDTO
{
    use Internal;

    public function append(mixed $value): self
    {
        $this->subject = Arr::append($this->subject, $value);

        return $this;
    }

    public function except(array $except_keys): self
    {
        $this->subject = Arr::except($this->subject, $except_keys);

        return $this;
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

    public function hasValue(mixed $value): bool
    {
        return Arr::hasValue($this->subject, $value);
    }

    public function isAssociative(): bool
    {
        return Arr::isAssociative($this->subject);
    }

    /**
     * @param array $only_keys
     * @return $this
     * @throws FailedToParseArrayKey
     */
    public function only(array $only_keys): self
    {
        $this->subject = Arr::only($this->subject, $only_keys);

        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     * @throws FailedToParseArrayKey
     */
    public function prepend(mixed $value): self
    {
        $this->subject = Arr::only($this->subject, $value);

        return $this;
    }

    public function recursiveJoin(array $array, array ...$arrays): self
    {
        $this->subject = Arr::recursiveJoin($this->subject, $array, ...$arrays);

        return $this;
    }

    public function searchValueKey(mixed $value): int|string|null
    {
        return Arr::searchValueKey($this->subject, $value);
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
