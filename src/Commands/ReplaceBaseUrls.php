<?php

namespace Wordless\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exceptions\DotEnvNotSetException;
use Wordless\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Helpers\Environment;

class ReplaceBaseUrls extends WordlessCommand
{
    private const BASE_URLS_TO_SEARCH_FOR_REPLACING = 'base_urls';

    protected static $defaultName = 'replace:base_urls';

    private string $app_url;
    private array $base_urls_to_search;

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
     * @return int
     * @throws WpCliCommandReturnedNonZero
     */
    protected function runIt(): int
    {
        $search_urls_string_list = implode(', ', $this->base_urls_to_search);
        $this->writeWhenVerbose("Searching for $search_urls_string_list to replace by $this->app_url");

        $this->runDatabaseSearchReplace();

        return Command::SUCCESS;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws DotEnvNotSetException
     */
    protected function setup(InputInterface $input, OutputInterface $output)
    {
        parent::setup($input, $output);

        $app_url_env_variable_name = 'APP_URL';
        $this->base_urls_to_search = $this->defineBaseUrlsToSearch();

        if (($this->app_url = Environment::get($app_url_env_variable_name)) === null) {
            throw new DotEnvNotSetException(
                ".env seems to be not correctly set for application because \"$app_url_env_variable_name\" returned a not expected value."
            );
        }
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
        if (($return_var = $this->executeWordlessCommand(WpCliCaller::COMMAND_NAME, [
            WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $command,
        ], $this->output))) {
            throw new WpCliCommandReturnedNonZero($command, $return_var);
        }
    }
}