<?php

namespace Wordless\Contracts\Abstraction\Composer;

use Composer\IO\IOInterface;
use Composer\Script\Event;
use Wordless\Exceptions\AppHostAlreadySetOnDotEnv;
use Wordless\Exceptions\FailedToCopyDotEnvExampleIntoNewDotEnv;
use Wordless\Exceptions\FailedToRewriteDotEnvFile;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Exceptions\UnavaibleNginxServerName;
use Wordless\Helpers\Environment;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

trait SetHostFromNginx
{
    /**
     * @param Event $composerEvent
     * @return void
     * @throws FailedToRewriteDotEnvFile
     * @throws PathNotFoundException
     */
    public static function setHost(Event $composerEvent)
    {
        self::defineProjectPath($composerEvent->getComposer());

        try {
            self::setAppHostValueAtDotEnv(self::getNginxServerNameConfig(), $composerEvent->getIO());
        } catch (AppHostAlreadySetOnDotEnv|UnavaibleNginxServerName $exception) {
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
     * @throws UnavaibleNginxServerName
     */
    private static function getNginxServerNameConfig(): string
    {
        $default_message = 'Skipping automatically .env ' . self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE . ' setting.';

        try {
            $app_host = self::extractAppHostFromNginxConfig(
                $nginx_config_filepath = ProjectPath::docker('nginx/sites/app.conf')
            );

            if (!is_string($app_host)) {
                throw new UnavaibleNginxServerName(
                    "'server_name' not valid into $nginx_config_filepath file. $default_message"
                );
            }

            return $app_host;
        } catch (PathNotFoundException $exception) {
            throw new UnavaibleNginxServerName("{$exception->getMessage()} $default_message");
        }
    }

    private static function replaceAppHost(
        string $dot_env_filepath,
        string $app_host_value,
        IOInterface $composerIo
    ): string
    {
        if (!Str::contains(
            $dot_env_content = file_get_contents($dot_env_filepath),
            self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE . '='
        )) {
            $composerIo->write(
                self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE . " not found in $dot_env_filepath, writing it."
            );

            return preg_replace(
                '/^(.*)$/m',
                '$1' . PHP_EOL . self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE . "=$app_host_value",
                $dot_env_content,
                1
            );
        }

        $composerIo->write(
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
     * @param IOInterface $composerIo
     * @return void
     * @throws AppHostAlreadySetOnDotEnv
     * @throws FailedToRewriteDotEnvFile
     * @throws PathNotFoundException
     */
    private static function setAppHostValueAtDotEnv(string $app_host, IOInterface $composerIo)
    {
        try {
            $dot_env_filepath = Environment::createDotEnvFromExample();
        } catch (FailedToCopyDotEnvExampleIntoNewDotEnv $exception) {
            $dot_env_filepath = ProjectPath::root('.env');
        }

        Environment::loadDotEnv();

        if (!empty($host = Environment::get(self::WORDLESS_APP_HOST_DOT_ENV_VARIABLE))) {
            throw new AppHostAlreadySetOnDotEnv($host);
        }

        if (file_put_contents(
                $dot_env_filepath,
                $dot_env_content = self::replaceAppHost($dot_env_filepath, $app_host, $composerIo)
            ) === false) {
            throw new FailedToRewriteDotEnvFile($dot_env_filepath, $dot_env_content);
        }
    }
}
