<?php

namespace Wordless\Application\Helpers\Environment\Traits;

trait Internal
{
    /**
     * @param string $value
     * @return string[]
     */
    private static function findReferenceInValue(string $value): array
    {
        preg_match_all('/^\S+=[^\s"]*\$\{(.+)}[^\s"]*$/m', $value, $output_array);

        return $output_array[1] ?? [];
    }

    private static function mountPackageVariablesContentToDotEnv(array $variables): string
    {
        $package_variables_content = '';

        foreach ($variables as $variable_name => $variable_value) {
            if (!is_string($variable_name)) {
                $package_variables_content .=
                    $variable_value !== null ? "$variable_value=#$variable_value" . PHP_EOL : '';
                continue;
            }

            if ($variable_value === null) {
                continue;
            }

            $package_variables_content .= "$variable_name=$variable_value" . PHP_EOL;
        }

        return $package_variables_content;
    }

    private static function resolveReferences(string $value): string
    {
        do {
            $referenced_dot_env_variable_names = self::findReferenceInValue($value);

            foreach ($referenced_dot_env_variable_names as $referenced_dot_env_variable_name) {
                $value = preg_replace(
                    '/^(\S+=[^\s"]*)\$\{.+}([^\s"]*)$/m',
                    '$1' . self::get($referenced_dot_env_variable_name) . '$2',
                    $value
                );
            }
        } while (!empty($referenced_dot_env_variable_names));

        return $value;
    }

    private static function retrieveValue(string $key, mixed $default = null): mixed
    {
        if (($value = getenv($key)) === false) {
            $value = $_ENV[$key] ?? $default;
        }

        if (!is_string($value)) {
            return $value;
        }

        return self::resolveReferences($value);
    }

    private static function returnTypedValue(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        return match (strtoupper($value)) {
            'TRUE' => true,
            'FALSE' => false,
            'NULL' => null,
            default => $value,
        };
    }
}
