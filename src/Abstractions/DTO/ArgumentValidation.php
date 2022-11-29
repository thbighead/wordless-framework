<?php

namespace Wordless\Abstractions\DTO;

use Symfony\Component\Validator\Constraint;
use Wordless\Adapters\Response;
use Wordless\Adapters\Validator;
use Wordless\Helpers\Debugger;
use WP_REST_Request;

class ArgumentValidation
{
    private const DEFAULT_KEY = 'default';
    private const REQUIRED_KEY = 'required';
    private const SANITIZATION_KEY = 'sanitize_callback';
    private const VALIDATION_KEY = 'validate_callback';

    private string $argument_name;
    private array $arrayForm = [
        self::REQUIRED_KEY => false,
    ];
    private array $validationRules;

    public static function mount(string $argument_name, array $validationRules): self
    {
        return new static($argument_name, $validationRules);
    }

    /**
     * @param string $argument_name
     * @param Constraint[] $validationRules
     */
    public function __construct(string $argument_name, array $validationRules)
    {
        $this->argument_name = $argument_name;
        $this->validationRules = $validationRules;
    }

    public function getArgumentName(): string
    {
        return $this->argument_name;
    }

    public function getArrayForm(Validator $validator): array
    {
        return $this->mountValidationRules($validator)->arrayForm;
    }

    public function setAsRequired(): ArgumentValidation
    {
        if (!isset($this->arrayForm[self::DEFAULT_KEY])) {
            $this->arrayForm[self::REQUIRED_KEY] = true;
        }

        return $this;
    }

    public function setDefaultValue($value): ArgumentValidation
    {
        $this->arrayForm[self::REQUIRED_KEY] = false;

        $this->arrayForm[self::DEFAULT_KEY] = isset($this->arrayForm[self::SANITIZATION_KEY]) ?
            $this->arrayForm[self::SANITIZATION_KEY]($value) : $value;

        return $this;
    }

    public function setSanitizationFunction(callable $sanitizationFunction): ArgumentValidation
    {
        $this->arrayForm[self::SANITIZATION_KEY] = $sanitizationFunction;

        if (isset($this->arrayForm[self::DEFAULT_KEY])) {
            $this->arrayForm[self::DEFAULT_KEY] = $sanitizationFunction($this->arrayForm[self::DEFAULT_KEY]);
        }

        return $this;
    }

    private function mountValidationRules(Validator $validator): ArgumentValidation
    {
        $this->arrayForm[self::VALIDATION_KEY] = function (
            $argument_value,
            WP_REST_Request $request,
            string $argument_name
        ) use ($validator) {
            $validationErrors = $validator->validateField(
                $argument_name,
                $argument_value,
                $this->validationRules
            );

            if ($validationErrors->count() > 0) {
                return Response::error(
                    $status_code = Response::HTTP_422_UNPROCESSABLE_ENTITY,
                    Response::HTTP_STATUS_TEXTS[$status_code],
                    (array)$validationErrors
                )->respond();
            }

            return true;
        };

        return $this;
    }
}
