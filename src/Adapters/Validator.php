<?php

namespace Wordless\Adapters;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    private ValidatorInterface $validator;
    private ConstraintViolationListInterface $validationErrors;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getValidationErrors(): ConstraintViolationListInterface
    {
        return $this->validationErrors;
    }

    /**
     * @param string $field_name
     * @param mixed $field_value
     * @param Constraint[] $rules
     * @return ConstraintViolationListInterface
     */
    public function validateField(string $field_name, $field_value, array $rules): ConstraintViolationListInterface
    {
        foreach ($rules as $rule) {
            $rule->addImplicitGroupName($field_name);
        }

        $validationErrors = $this->validator->validate($field_value, $rules, $field_name);

        isset($this->validationErrors) ?
            $this->validationErrors->addAll($validationErrors) :
            $this->validationErrors = $validationErrors;

        return $this->validationErrors;
    }
}
