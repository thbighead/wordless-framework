<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Exceptions\DotEnvNotSetException;

trait Console
{
    /**
     * @param Application $application
     * @return void
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
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
