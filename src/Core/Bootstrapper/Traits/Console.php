<?php

namespace Wordless\Core\Bootstrapper\Traits;

use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\LogicException;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;

trait Console
{
    /**
     * @param Application $application
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws LogicException
     * @throws PathNotFoundException
     */
    public static function bootConsole(Application $application): void
    {
        self::getInstance()->bootIntoSymfonyApplication($application);
    }

    /**
     * @param Application $application
     * @return void
     * @throws LogicException
     */
    private function bootIntoSymfonyApplication(Application $application): void
    {
        foreach ($this->loaded_providers as $provider) {
            foreach ($provider->registerCommands() as $command_namespace) {
                $command = new $command_namespace($command_namespace::COMMAND_NAME);

                if ($command->canRun()) {
                    $application->add($command);
                }
            }
        }

        try {
            $application->run();
        } catch (Exception $exception) {
            echo Str::finishWith($exception->getMessage(), "\n");
        }
    }
}
