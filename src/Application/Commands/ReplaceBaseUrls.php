<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Application\Commands\Exceptions\DotEnvNotSetException;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Helpers\Environment;
use Wordless\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\OptionDTO;

class ReplaceBaseUrls extends ConsoleCommand
{
    use RunWpCliCommand;

    final public const COMMAND_NAME = 'replace:base_urls';
    private const BASE_URLS_TO_SEARCH_FOR_REPLACING = 'base_urls';

    protected static $defaultName = self::COMMAND_NAME;

    private string $app_url;
    private array $base_urls_to_search;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            new ArgumentDTO(
                self::BASE_URLS_TO_SEARCH_FOR_REPLACING,
                'Base URLs to search and replace by the defined by the application (.env APP_URL)',
                ArgumentMode::array_optional,
                [Environment::COMMONLY_DOT_ENV_DEFAULT_VALUES['APP_URL']]
            ),
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

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    protected function runIt(): int
    {
        $search_urls_string_list = implode(', ', $this->base_urls_to_search);
        $this->writeInfoWhenVerbose("Searching for $search_urls_string_list to replace by $this->app_url");

        $this->runDatabaseSearchReplace();

        return Command::SUCCESS;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws DotEnvNotSetException
     */
    protected function setup(InputInterface $input, OutputInterface $output): void
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
     * @throws ExceptionInterface
     * @throws WpCliCommandReturnedNonZero
     */
    private function runDatabaseSearchReplace(): void
    {
        foreach ($this->base_urls_to_search as $base_url_to_search) {
            $this->runWpCliCommand("search-replace '$base_url_to_search' '$this->app_url'");
        }
    }
}
