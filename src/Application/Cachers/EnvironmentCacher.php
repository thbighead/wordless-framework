<?php declare(strict_types=1);

namespace Wordless\Application\Cachers;

use Wordless\Application\Cachers\Exceptions\FailedToMountCacheArray;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToLoadDotEnv;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Cacher;

class EnvironmentCacher extends Cacher
{
    protected function cacheFilename(): string
    {
        return 'environment.php';
    }

    /**
     * @return array
     * @throws FailedToMountCacheArray
     */
    protected function mountCacheArray(): array
    {
        return $this->parseDotEnvFileContent();
    }

    /**
     * @return array
     * @throws FailedToMountCacheArray
     */
    private function parseDotEnvFileContent(): array
    {
        $parsed_dot_env_content = [];

        try {
            $dot_env_content = file_get_contents(ProjectPath::root('.env'));

            preg_match_all('/^([^=]+)=.*$/m', $dot_env_content, $regex_parser_result);

            foreach ($regex_parser_result[1] as $env_key) {
                $env_key = trim($env_key);
                if (Str::beginsWith($env_key, Environment::DOT_ENV_COMMENT_MARK)) {
                    continue;
                }

                $parsed_dot_env_content[$env_key] = Environment::getWithoutCache($env_key);
            }
        } catch (CannotResolveEnvironmentGet|PathNotFoundException $exception) {
            throw new FailedToMountCacheArray($exception);
        }

        return $parsed_dot_env_content;
    }
}
