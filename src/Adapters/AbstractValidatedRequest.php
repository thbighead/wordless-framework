<?php

namespace Wordless\Adapters;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Wordless\Exceptions\ValidationRuleMustBeConstraint;
use Wordless\Exceptions\ValidationRulesKeyMustBeString;
use Wordless\Exceptions\ValidationRulesValueMustBeArray;

abstract class AbstractValidatedRequest extends Request
{
    abstract protected function fieldsRules(): array;

    private const VALIDATED_AS_INVALID_KEY = 'invalid';
    private const VALIDATED_AS_UNKNOWN_KEY = 'unknown';
    private const VALIDATED_AS_VALID_KEY = 'valid';

    /** @var array $validationUsefulBag use this to store any useful result calculated by your validation to avoid recalculating into Controller */
    protected array $validationUsefulBag = [];
    /** @var array<array<Constraint>> $fieldsRules */
    private array $fieldsRules;
    private array $validated_fields = [
        self::VALIDATED_AS_INVALID_KEY => [],
        self::VALIDATED_AS_UNKNOWN_KEY => [],
        self::VALIDATED_AS_VALID_KEY => [],
    ];
    private ValidatorInterface $validator;
    private ConstraintViolationList $violations;

    public function __construct($method = '', $route = '', $attributes = array())
    {
        parent::__construct($method, $route, $attributes);

        $this->validator = Validation::createValidator();
        $this->violations = new ConstraintViolationList;
        $this->validated_fields[self::VALIDATED_AS_UNKNOWN_KEY] = $this->get_params();
    }

    public function validate(): bool
    {
        return $this->validateFields();
    }

    public function getValidatedField(string $field, $default = null)
    {
        return $this->validated_fields[self::VALIDATED_AS_INVALID_KEY][$field] ??
            $this->validated_fields[self::VALIDATED_AS_UNKNOWN_KEY][$field] ??
            $this->validated_fields[self::VALIDATED_AS_VALID_KEY][$field] ??
            $default;
    }

    public function getValidatedAsInvalidFields(): array
    {
        return $this->validated_fields[self::VALIDATED_AS_INVALID_KEY];
    }

    public function getValidatedAsUnknownFields(): array
    {
        return $this->validated_fields[self::VALIDATED_AS_UNKNOWN_KEY];
    }

    public function getValidatedAsValidFields(): array
    {
        return $this->validated_fields[self::VALIDATED_AS_VALID_KEY];
    }

    public function getValidatedFields(): array
    {
        return $this->validated_fields;
    }

    /**
     * @return ConstraintViolationList
     */
    public function getViolations(): ConstraintViolationList
    {
        return $this->violations;
    }

    /**
     * @return array<array<Constraint>>
     */
    private function getFieldsRules(): array
    {
        return $this->fieldsRules ?? $this->fieldsRules = $this->fieldsRules();
    }

    private function validateFields(): bool
    {
        foreach ($this->getFieldsRules() as $field => $rules) {
            $this->validateFieldRules($field, $rules);

            unset($this->validated_fields[self::VALIDATED_AS_UNKNOWN_KEY][$field]);

            $this->violations->addAll(
                $fieldViolations = $this->validator->validate($field_value = $this->get_param($field), $rules)
            );

            $this->validated_fields
            [$fieldViolations->count() != 0 ? self::VALIDATED_AS_INVALID_KEY : self::VALIDATED_AS_VALID_KEY]
            [$field] = $field_value;
        }

        return $this->violations->count() === 0;
    }

    private function validateFieldRules($field, $rules)
    {
        if (!is_string($field)) {
            throw new ValidationRulesKeyMustBeString($field);
        }

        if (!is_array($rules)) {
            throw new ValidationRulesValueMustBeArray($rules);
        }

        foreach ($rules as $rule) {
            if (!($rule instanceof Constraint)) {
                throw new ValidationRuleMustBeConstraint($rule);
            }
        }
    }
}
