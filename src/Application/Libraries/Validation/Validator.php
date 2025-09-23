<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    private ValidatorInterface $validator;

    /** @var array<string, ConstraintViolationListInterface> $validationErrors */
    private array $validationErrors = [];

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    /**
     * @return array<string, ConstraintViolationListInterface>
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    public function hasErrors(): bool
    {
        /** @var ConstraintViolationList $fieldErrorsList */
        foreach ($this->validationErrors as $fieldErrorsList) {
            if ($fieldErrorsList->count() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, string[]>
     */
    public function mountViolationsMessagesByField(): array
    {
        $violations = [];

        foreach ($this->validationErrors as $field => $validationErrors) {
            if ($validationErrors->count() === 0) {
                continue;
            }

            $violations[$field] = [];

            foreach ($validationErrors as $validationError) {
                /** @var ConstraintViolation $validationError */
                $violations[$field][] = $validationError->getMessage();
            }
        }

        return $violations;
    }

    /**
     * @param string $field_name
     * @param mixed $field_value
     * @param Constraint[] $rules
     * @return ConstraintViolationListInterface
     */
    public function validateField(
        string $field_name,
        mixed  $field_value,
        array  $rules
    ): ConstraintViolationListInterface
    {
        return $this->validationErrors[$field_name] = $this->validator->validate($field_value, $rules);
    }
}
