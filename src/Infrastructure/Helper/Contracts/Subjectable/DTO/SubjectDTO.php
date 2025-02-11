<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO;

use Throwable;
use Wordless\Application\Helpers\Arr;

class SubjectDTO
{
    private readonly mixed $original_subject;

    public function __construct(protected mixed $subject)
    {
        $this->original_subject = $this->subject;
    }

    public function getOriginalSubject(): mixed
    {
        return $this->original_subject;
    }

    public function getSubject(): mixed
    {
        return $this->subject;
    }

    protected function resolveArgumentValueByProperty(
        string $argument_name,
        mixed  $argument_value,
        string $property_name,
        array  $func_get_args,
        array  $get_defined_vars
    ): mixed
    {
        try {
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->$property_name;

            $encoding_index = Arr::getIndexOfKey(get_defined_vars(), $argument_name);

            if (!is_int($encoding_index) || !Arr::hasKey(func_get_args(), $encoding_index)) {
                return $this->$property_name;
            }
        } catch (Throwable) {
        }

        return $argument_value;
    }
}
