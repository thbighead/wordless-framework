<?php

namespace Wordless\Contracts\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

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
