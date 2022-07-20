<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class ThemeNpmCaller extends WordlessCommand
{
    public const COMMAND_NAME = 'theme:npm';
    public const NPM_FULL_COMMAND_STRING_ARGUMENT_NAME = 'npm_full_command_string';

    protected static $defaultName = self::COMMAND_NAME;

    protected function arguments(): array
    {
        return [
            [
                self::ARGUMENT_DEFAULT_FIELD => '',
                self::ARGUMENT_DESCRIPTION_FIELD => 'A string containing exactly what you want to run with "npm"',
                self::ARGUMENT_MODE_FIELD => InputArgument::OPTIONAL,
                self::ARGUMENT_NAME_FIELD => self::NPM_FULL_COMMAND_STRING_ARGUMENT_NAME,
            ],
        ];
    }

    protected function description(): string
    {
        return 'Runs a NPM command automatically inside the current WordPress theme.';
    }

    /**
     * @return int
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $npm_full_command_string = $this->input->getArgument(self::NPM_FULL_COMMAND_STRING_ARGUMENT_NAME);
        $current_wordpress_theme_path = ProjectPath::theme();
        $full_command = "cd $current_wordpress_theme_path && npm $npm_full_command_string";

        $this->writelnWhenVerbose("Executing $full_command...");

        return $this->executeCommand($full_command);
    }

    protected function help(): string
    {
        return 'Instead of changing the current directory to the theme folder and executing NPM commands there, just run php console theme:npm "{command}".';
    }

    protected function options(): array
    {
        return [];
    }
}
