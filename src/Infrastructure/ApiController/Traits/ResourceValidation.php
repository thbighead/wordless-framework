<?php

namespace Wordless\Controller\Traits;

use Symfony\Component\Validator\Constraint;
use Wordless\Application\Validator;
use Wordless\Exceptions\ValidationError;

trait ResourceValidation
{
    private Validator $validator;

    protected function getValidator(): Validator
    {
        return $this->validator ?? $this->validator = new Validator;
    }

    /**
     * @return array<string, Constraint[]>
     */
    protected function validateResourceDestroy(): array
    {
        return [];
    }

    /**
     * @return array<string, Constraint[]>
     */
    protected function validateResourceIndex(): array
    {
        return [];
    }

    /**
     * @return array<string, Constraint[]>
     */
    protected function validateResourceShow(): array
    {
        return [];
    }

    /**
     * @return array<string, Constraint[]>
     */
    protected function validateResourceStore(): array
    {
        return [];
    }

    /**
     * @return array<string, Constraint[]>
     */
    protected function validateResourceUpdate(): array
    {
        return [];
    }

    /**
     * @param array<string, Constraint[]> $validationRules
     * @param array<string, mixed> $arguments
     * @return array<string, mixed>
     * @throws ValidationError
     */
    final protected function validateArguments(array $validationRules, array $arguments): array
    {
        $validated = [];

        foreach ($validationRules as $field => $rules) {
            $this->getValidator()->validateField($field, $field_value = $arguments[$field] ?? null, $rules);

            $validated[$field] = $field_value;
        }

        if ($this->getValidator()->hasErrors()) {
            throw new ValidationError($this->getValidator()->mountViolationsMessagesByField());
        }

        return $validated;
    }
}
