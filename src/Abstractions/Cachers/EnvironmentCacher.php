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

        preg_match_all('/^([^=]+)=.*$/m', $dot_env_content, $regex_parser_result);

        foreach ($regex_parser_result[1] as $env_key) {
            $env_key = trim($env_key);
            if (Str::beginsWith($env_key, Environment::DOT_ENV_COMMENT_MARK)) {
                continue;
            }

            $parsed_dot_env_content[$env_key] = Environment::get($env_key);
        }

        return $parsed_dot_env_content;
    }
}