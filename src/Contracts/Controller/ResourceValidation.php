<?php

namespace Wordless\Contracts\Controller;

use Wordless\Abstractions\DTO\ArgumentValidation;
use Wordless\Adapters\Validator;

trait ResourceValidation
{
    private Validator $validator;

    protected function getValidator(): Validator
    {
        return $this->validator ?? $this->validator = new Validator;
    }

    /**
     * @return ArgumentValidation[]
     */
    protected function validateResourceDestroy(): array
    {
        return [];
    }

    /**
     * @return ArgumentValidation[]
     */
    protected function validateResourceIndex(): array
    {
        return [];
    }

    /**
     * @return ArgumentValidation[]
     */
    protected function validateResourceShow(): array
    {
        return [];
    }

    /**
     * @return ArgumentValidation[]
     */
    protected function validateResourceStore(): array
    {
        return [];
    }

    /**
     * @return ArgumentValidation[]
     */
    protected function validateResourceUpdate(): array
    {
        return [];
    }

    /**
     * @param ArgumentValidation[] $argumentsValidations
     * @return array
     */
    final protected function mountRequestArgumentValidationArray(array $argumentsValidations): array
    {
        $arguments_validations = [];

        foreach ($argumentsValidations as $argumentValidations) {
            $arguments_validations[$argumentValidations->getArgumentName()] =
                $argumentValidations->getArrayForm($this->validator);
        }

        return $arguments_validations;
    }
}
