<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessInstall\Traits;

use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToPutFileContent;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

trait ForFramework
{
    /**
     * @return void
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToPutFileContent
     * @throws PathNotFoundException
     */
    private function generateEmptyWordlessTheme(): void
    {
        DirectoryFiles::createFileAt(
            ProjectPath::wpContent() . '/themes/wordless/style.css',
            <<<CSS
/*
Theme Name: Wordless
Author: Thales Nathan
*/

CSS
        );
        DirectoryFiles::createFileAt(
            ProjectPath::wpContent() . '/themes/wordless/index.php',
            <<<PHP
<?php declare(strict_types=1);

PHP
        );
    }
}
