<?php

namespace Wordless\Application\Commands\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Exceptions\PathNotFoundException;

trait LoadWpConfig
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws PathNotFoundException
     */
    protected function setup(InputInterface $input, OutputInterface $output)
    {
        /** @noinspection PhpMultipleClassDeclarationsInspection */
        parent::setup($input, $output);

        include_once ProjectPath::wpCore('wp-config.php');
    }
}
