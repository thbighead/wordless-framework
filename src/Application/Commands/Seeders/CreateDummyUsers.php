<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders;


use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Seeders\Contracts\BaseCreateDummyCommand;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Commands\WpCliCaller;

class CreateDummyUsers extends BaseCreateDummyCommand
{
    use LoadWpConfig;

    public const COMMAND_NAME = 'generate:categories';

    private const HOW_MANY_USERS = 20;

    protected function description(): string
    {
        return 'A custom command to create dummy categories';
    }

    /**
     * @return int
     * @throws Exception
     * @throws ExceptionInterface
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Creating Users...', function () {
            for ($i = 0; $i < $this->getTotalCategoriesToCreate(); $i++) {
                $user_name = $this->faker->userName();
                $user_email = $this->faker->safeEmail();
                $full_command = "user create $user_name $user_email --porcelain --quiet";

                $this->callConsoleCommand(
                    WpCliCaller::COMMAND_NAME,
                    [WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $full_command],
                );
            }

            $this->output->write(self::PROGRESS_MARK);
        });

        return Command::SUCCESS;
    }

    private function getTotalCategoriesToCreate(): int
    {
        return (int)$this->input->getOption(self::OPTION_TOTAL) ?? self::HOW_MANY_USERS;
    }
}
