<?php declare(strict_types=1);

namespace Wordless\Application\Cachers;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Cacher;

class EnvironmentCacher extends Cacher
{
    protected function cacheFilename(): string
    {
        return 'environment.php';
    }

    /**
     * @return array
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathNotFoundException
     */
    protected function mountCacheArray(): array
    {
        return $this->parseDotEnvFileContent();
    }

    /**
     * @return array
     * @throws DotEnvNotSetException
     * @throws FormatException
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

            $parsed_dot_env_content[$env_key] = Environment::getWithoutCache($env_key);
        }

        return $parsed_dot_env_content;
    }
}
