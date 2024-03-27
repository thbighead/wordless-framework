<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

trait LoadWpConfig
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws PathNotFoundException
     */
    protected function setup(InputInterface $input, OutputInterface $output): void
    {
        /** @noinspection PhpMultipleClassDeclarationsInspection */
        parent::setup($input, $output);

        include_once ProjectPath::wpCore('wp-config.php');
    }
}
