<?php

namespace Wordless\Contracts\Command;

use Wordless\Exception\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

trait RunMigrateCommand
{
    /**
     * @return void
     * @throws PathNotFoundException
     */
    private function upMigrations()
    {
        include_once ProjectPath::wpCore('wp-config.php');
        $this->executeWordlessCommand('migrate', [], $this->output);
    }
}