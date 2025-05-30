<?php declare(strict_types=1);

namespace Wordless\Application\Styles;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle\GlobalEnqueueableStyle;

class AdminBarEnvironmentFlagStyle extends GlobalEnqueueableStyle
{
    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws PathNotFoundException
     */
    public static function mountSymlinkTargetRelativePath(): string
    {
        return '../' . Str::after(
                ProjectPath::vendorPackageRoot(Environment::isNotLocal() ? 'dist' : 'assets'),
                Str::finishWith(ProjectPath::root(), DIRECTORY_SEPARATOR)
            );
    }

    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    protected function filename(): string
    {
        $filename = 'env-flag';

        if (Environment::isNotLocal()) {
            $filename .= '.min';
        }

        return "$filename.css";
    }

    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    protected function mountFileUrl(): string
    {
        return Link::raw("vendor/wordless/dist/css/{$this->filename()}");
    }
}
