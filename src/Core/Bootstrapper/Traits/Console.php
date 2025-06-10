<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadErrorReportingConfiguration;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Console\Exceptions\FailedToAddConsoleCommand;
use Wordless\Core\Bootstrapper\Traits\Console\Exceptions\FailedToBootApplication;

trait Console
{
    /**
     * @param Application $application
     * @return void
     * @throws FailedToBootApplication
     */
    public static function bootConsole(Application $application): void
    {
        try {
            self::getInstance()->bootIntoSymfonyApplication($application);
        } catch (FailedToLoadErrorReportingConfiguration|FailedToAddConsoleCommand $exception) {
            throw new FailedToBootApplication($application, $exception);
        }
    }

    /**
     * @param Application $application
     * @return void
     * @throws FailedToAddConsoleCommand
     */
    private function bootIntoSymfonyApplication(Application $application): void
    {
        foreach ($this->loaded_providers as $provider) {
            foreach ($provider->registerCommands() as $command_namespace) {
                $command = new $command_namespace($command_namespace::COMMAND_NAME);

                if ($command->canRun()) {
                    try {
                        $application->add($command);
                    } catch (LogicException $exception) {
                        throw new FailedToAddConsoleCommand($command, $exception);
                    }
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
