<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits;

use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud;
use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Loader;

trait WithAcfs
{
    use Crud;
    use Loader;

    /**
     * @param string $function_name
     * @return string
     * @throws InvalidAcfFunction
     */
    private function validateAcfFunction(string $function_name): string
    {
        if (!function_exists($function_name)) {
            throw new InvalidAcfFunction($function_name);
        }

        return $function_name;
    }
}
