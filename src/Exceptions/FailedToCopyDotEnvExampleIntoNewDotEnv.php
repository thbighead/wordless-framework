<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class FailedToCopyDotEnvExampleIntoNewDotEnv extends Exception
{
    public function __construct(
        string    $dot_env_example_filepath,
        ?string   $new_dot_env_filepath = null,
        Throwable $previous = null
    )
    {
        parent::__construct(
            $this->mountMessage($dot_env_example_filepath, $new_dot_env_filepath),
            1,
            $previous
        );
    }

    private function mountMessage(string $dot_env_example_filepath, ?string $new_dot_env_filepath = null): string
    {
        return $new_dot_env_filepath === null ?
            "$dot_env_example_filepath already exists." :
            "Couldn't copy $dot_env_example_filepath into $new_dot_env_filepath.";
    }
}
