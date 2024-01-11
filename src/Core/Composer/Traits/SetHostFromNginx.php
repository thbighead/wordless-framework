<?php declare(strict_types=1);

namespace Wordless\Core\Composer\Traits;

use Composer\Script\Event;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToCopyDotEnvExampleIntoNewDotEnv;
use Wordless\Application\Helpers\Environment\Exceptions\FailedToRewriteDotEnvFile;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Composer\Exceptions\AppHostAlreadySetOnDotEnv;
use Wordless\Core\Composer\Traits\SetHostFromNginx\Exceptions\UnavailableNginxServerName;
use Wordless\Core\Exceptions\DotEnvNotSetException;

trait SetHostFromNginx
{
    /**
     * @param Event $composerEvent
     * @return void
     * @throws DotEnvNotSetException
     * @throws FailedToRewriteDotEnvFile
     * @throws PathNotFoundException
     */
    public static function setHost(Event $composerEvent): void
    {
        static::initializeIo($composerEvent);
        self::defineProjectPath($composerEvent->getComposer());

        try {
            self::setAppHostValueAtDotEnv(self::getNginxServerNameConfig());
        } catch (AppHostAlreadySetOnDotEnv|UnavailableNginxServerName $exception) {
            $composerEvent->getIO()->write($exception->getMessage());
        }
    }

    private static function extractAppHostFromNginxConfig(string $nginx_config_filepath): ?string
    {
        preg_match_all(
            '/server_name\s+([^\s;]+);+/',
            file_get_contents($nginx_config_filepath),
            $output_array
        );

        return $output_array[1][0] ?? null;
    }

    /**
     * @return string
     * @throws UnavailableNginxServerName
     */
    private static function getNginxServerNameConfig(): string
    {
        $default_message = 'Skipping automatically .env ' . self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE . ' setting.';

        try {
            $app_host = self::extractAppHostFromNginxConfig(
                $nginx_config_filepath = ProjectPath::docker('nginx/sites/app.conf')
            );

            if (!is_string($app_host)) {
                throw new UnavailableNginxServerName(
                    "'server_name' not valid into $nginx_config_filepath file. $default_message"
                );
            }

            return $app_host;
        } catch (PathNotFoundException $exception) {
            throw new UnavailableNginxServerName("{$exception->getMessage()} $default_message");
        }
    }

    private static function replaceAppHost(string $dot_env_filepath, string $app_host_value): string
    {
        if (!Str::contains(
            $dot_env_content = file_get_contents($dot_env_filepath),
            self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE . '='
        )) {
            static::getIo()->write(
                self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE . " not found in $dot_env_filepath, writing it."
            );

            return preg_replace(
                '/^(.*)$/m',
                '$1' . PHP_EOL . self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE . "=$app_host_value",
                $dot_env_content,
                1
            );
        }

        static::getIo()->write(
            self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE
            . " found empty in $dot_env_filepath, writing it as $app_host_value."
        );

        return preg_replace(
            '/^(' . self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE . '=)$/m',
            "$1$app_host_value",
            $dot_env_content
        );
    }

    /**
     * @param string $app_host
     * @return void
     * @throws AppHostAlreadySetOnDotEnv
     * @throws FailedToRewriteDotEnvFile
     * @throws PathNotFoundException
     * @throws DotEnvNotSetException
     */
    private static function setAppHostValueAtDotEnv(string $app_host): void
    {
        try {
            $dot_env_filepath = Environment::createDotEnvFromExample();
        } catch (FailedToCopyDotEnvExampleIntoNewDotEnv) {
            $dot_env_filepath = ProjectPath::root('.env');
        }

        Environment::loadDotEnv();

        if (!empty($host = Environment::get(self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE))) {
            throw new AppHostAlreadySetOnDotEnv($host);
        }

        Environment::rewriteDotEnvFile($dot_env_filepath, self::replaceAppHost($dot_env_filepath, $app_host));
    }
}
