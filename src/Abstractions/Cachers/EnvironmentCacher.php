<?php

namespace Wordless\Abstractions\Cachers;

use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Environment;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class EnvironmentCacher extends BaseCacher
{
    protected function cacheFilename(): string
    {
        return 'environment.php';
    }

    /**
     * @return array
     * @throws PathNotFoundException
     */
    protected function mountCacheArray(): array
    {
        return $this->parseDotEnvFileContent();
    }

    /**
     * @return array
     * @throws PathNotFoundException
     */
    private function parseDotEnvFileContent(): array
    {
        $parsed_dot_env_content = [];
        $dot_env_content = file_get_contents(ProjectPath::root('.env'));

        preg_match_all('/([^=\s]+)=([^\r]*)/', $dot_env_content, $regex_parser_result);

        foreach ($regex_parser_result[1] as $index => $env_key) {
            if (Str::beginsWith($env_key, Environment::DOT_ENV_COMMENT_MARK)) {
                continue;
            }

            $env_value = $regex_parser_result[2][$index] ?? null;

            if ($env_value === null) {
                continue;
            }

            $parsed_dot_env_content[$env_key] = $env_value;
        }

        return $parsed_dot_env_content;
    }
}