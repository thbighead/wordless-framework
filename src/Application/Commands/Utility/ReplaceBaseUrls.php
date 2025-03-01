<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Helpers\Environment;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class ReplaceBaseUrls extends ConsoleCommand
{
    use RunWpCliCommand;

    final public const COMMAND_NAME = 'replace:base_urls';
    private const BASE_URLS_TO_SEARCH_FOR_REPLACING = 'base_urls';

    private string $app_url;
    /** @var string[] $base_urls_to_search */
    private array $base_urls_to_search;

    /**
     * @return ArgumentDTO[]
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathException
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::BASE_URLS_TO_SEARCH_FOR_REPLACING,
                'Base URLs to search and replace by the defined by the application (.env APP_URL)',
                ArgumentMode::array_optional,
                [Environment::get('APP_URL')]
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
        return [
            ...$this->mountRunWpCliOptions(),
        ];
    }

    /**
     * @return int
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
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
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws PathException
     */
    protected function setup(InputInterface $input, OutputInterface $output): void
    {
        parent::setup($input, $output);

        $app_url_env_variable_name = 'APP_URL';
        $this->base_urls_to_search = $this->defineBaseUrlsToSearch();

        if (($this->app_url = Environment::get($app_url_env_variable_name)) === null) {
            throw new DotEnvNotSetException(
                ".env seems incorrect for application because \"$app_url_env_variable_name\" returned a non expected value."
            );
        }
    }

    /**
     * @return string[]
     * @throws InvalidArgumentException
     */
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
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function runDatabaseSearchReplace(): void
    {
        foreach ($this->base_urls_to_search as $base_url_to_search) {
            $this->runWpCliCommand("search-replace '$base_url_to_search' '$this->app_url'");
        }
    }
}
