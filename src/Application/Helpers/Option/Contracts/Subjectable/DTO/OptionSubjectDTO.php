<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Option\Contracts\Subjectable\DTO;

use Wordless\Application\Helpers\Expect\Contracts\Subjectable\DTO\ExpectSubjectDTO;
use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\Option\Contracts\Subjectable\DTO\OptionSubjectDTO\Traits\Internal;
use Wordless\Application\Helpers\Option\Exception\FailedToCreateOption;
use Wordless\Application\Helpers\Option\Exception\FailedToDeleteOption;
use Wordless\Application\Helpers\Option\Exception\FailedToFindOption;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

final class OptionSubjectDTO extends SubjectDTO
{
    use Internal;

    public function create(mixed $option_value, bool $autoload = true): ?self
    {
        if (Option::create($this->subject, $option_value, $autoload)) {
            return $this;
        }

        return null;
    }

    /**
     * @param mixed $option_value
     * @param bool $autoload
     * @return $this
     * @throws FailedToCreateOption
     */
    public function createOrFail(mixed $option_value, bool $autoload = true): self
    {
        Option::createOrFail($this->subject, $option_value, $autoload);

        return $this;
    }

    public function createOrUpdate(mixed $option_value, ?bool $autoload = null): ?self
    {
        if (Option::createOrUpdate($this->subject, $option_value, $autoload)) {
            return $this;
        }

        return null;
    }

    /**
     * @param mixed $option_value
     * @param bool $autoload
     * @return $this
     * @throws FailedToUpdateOption
     */
    public function createUpdateOrFail(mixed $option_value, ?bool $autoload = null): self
    {
        Option::createUpdateOrFail($this->subject, $option_value, $autoload);

        return $this;
    }

    public function delete(): ?self
    {
        if (Option::delete($this->subject)) {
            return $this;
        }

        return null;
    }

    /**
     * @return $this
     * @throws FailedToDeleteOption
     */
    public function deleteOrFail(): self
    {
        Option::deleteOrFail($this->subject);

        return $this;
    }

    public function expect(mixed $default = null): ExpectSubjectDTO
    {
        try {
            return new ExpectSubjectDTO($this->getOrFail());
        } catch (FailedToFindOption) {
            return $default;
        }
    }

    /**
     * @return ExpectSubjectDTO
     * @throws FailedToFindOption
     */
    public function expectOrFail(): ExpectSubjectDTO
    {
        return new ExpectSubjectDTO($this->getOrFail());
    }

    public function of(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function get(mixed $default = null): mixed
    {
        return Option::get($this->subject, $default);
    }

    /**
     * @return mixed
     * @throws FailedToFindOption
     */
    public function getOrFail(): mixed
    {
        return Option::getOrFail($this->subject);
    }

    public function update(mixed $option_value, ?bool $autoload = null): ?self
    {
        if (Option::update($this->subject, $option_value, $autoload)) {
            return $this;
        }

        return null;
    }

    /**
     * @param mixed $option_value
     * @param bool|null $autoload
     * @return $this
     * @throws FailedToUpdateOption
     */
    public function updateOrFail(mixed $option_value, ?bool $autoload = null): self
    {
        Option::updateOrFail($this->subject, $option_value, $autoload);

        return $this;
    }
}
