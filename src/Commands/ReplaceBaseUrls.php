<?php

namespace Wordless\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exception\DotEnvNotSetException;
use Wordless\Exception\WpCliCommandReturnedNonZero;
use Wordless\Helpers\Environment;

class ReplaceBaseUrls extends WordlessCommand
{
    private const BASE_URLS_TO_SEARCH_FOR_REPLACING = 'base_urls';

    protected static $defaultName = 'replace:base_urls';

    private string $app_url;
    private array $base_urls_to_search;
    private InputInterface $input;
    private OutputInterface $output;
    private Command $wpCliCommand;

    protected function arguments(): array
    {
        return [
            [
                self::ARGUMENT_DEFAULT_FIELD => [Environment::COMMONLY_DOT_ENV_DEFAULT_VALUES['APP_URL']],
                self::ARGUMENT_DESCRIPTION_FIELD =>
                    'Base URLs to search and replace by the defined by the application (.env APP_URL)',
                self::ARGUMENT_MODE_FIELD => InputArgument::IS_ARRAY,
                self::ARGUMENT_NAME_FIELD => self::BASE_URLS_TO_SEARCH_FOR_REPLACING,
            ],
        ];
    }

    protected function description(): string
    {
        return 'Replace specified base_urls by the base_url from this app.';
    }

    protected function help(): string
    {
        return 'Replace specified base_urls by the base_url from this app. This should run search-replace from WP-CLI';
    }

    protected function options(): array
    {
        return [];
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws DotEnvNotSetException
     * @throws WpCliCommandReturnedNonZero
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setup($input, $output);

        $search_urls_string_list = implode(', ', $this->base_urls_to_search);
        $this->writeWhenVerbose("Searching for $search_urls_string_list to replace by $this->app_url");

        $this->runDatabaseSearchReplace();

        return Command::SUCCESS;
    }

    private function defineBaseUrlsToSearch(): array
    {
        $base_urls_to_search = $this->input->getArgument(self::BASE_URLS_TO_SEARCH_FOR_REPLACING);

        foreach ($base_urls_to_search as &$url) {
            $url = rtrim($url, '/');
        }

        return $base_urls_to_search;
    }

    /**
     * @return void
     * @throws WpCliCommandReturnedNonZero
     */
    private function runDatabaseSearchReplace()
    {
        foreach ($this->base_urls_to_search as $base_url_to_search) {
            $this->runWpCliCommand("search-replace '$base_url_to_search' '$this->app_url'");
        }
    }

    /**
     * @param string $command
     * @return void
     * @throws WpCliCommandReturnedNonZero
     * @throws Exception
     */
    private function runWpCliCommand(string $command): void
    {
        if (($return_var = $this->wpCliCommand->run(new ArrayInput([
            WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $command,
        ]), $this->output))) {
            throw new WpCliCommandReturnedNonZero($command, $return_var);
        }

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws DotEnvNotSetException
     */
    private function setup(InputInterface $input, OutputInterface $output)
    {
        $app_url_env_variable_name = 'APP_URL';
        $this->input = $input;
        $this->output = $output;
        $this->wpCliCommand = $this->getApplication()->find(WpCliCaller::COMMAND_NAME);
        $this->base_urls_to_search = $this->defineBaseUrlsToSearch();

        if (($this->app_url = Environment::get($app_url_env_variable_name)) === null) {
            throw new DotEnvNotSetException(
                ".env seems to be not correctly set for application because \"$app_url_env_variable_name\" returned a not expected value."
            );
        }
    }

    private function writeWhenVerbose(string $message)
    {
        if ($this->output->isVerbose()) {
            $this->output->writeln($message);
        }
    }
}