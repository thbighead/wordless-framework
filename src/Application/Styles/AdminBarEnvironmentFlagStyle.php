<?php declare(strict_types=1);

namespace Wordless\Application\Styles;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Styles\AdminBarEnvironmentFlagStyle\Exceptions\CannotMountAdminEnvironmentFlagStyleSymlinkRelativePath;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle\GlobalEnqueueableStyle;

class AdminBarEnvironmentFlagStyle extends GlobalEnqueueableStyle
{
    /**
     * @return string
     * @throws CannotMountAdminEnvironmentFlagStyleSymlinkRelativePath
     */
    public static function mountSymlinkTargetRelativePath(): string
    {
        try {
            return '../' . Str::after(
                    ProjectPath::vendorPackageRoot(Environment::isNotLocal() ? 'dist' : 'assets'),
                    Str::finishWith(ProjectPath::root(), DIRECTORY_SEPARATOR)
                );
        } catch (CannotResolveEnvironmentGet|PathNotFoundException $exception) {
            throw new CannotMountAdminEnvironmentFlagStyleSymlinkRelativePath($exception);
        }
    }

    /**
     * @return string
     * @throws CannotResolveEnvironmentGet
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
     * @throws CannotResolveEnvironmentGet
     */
    protected function mountFileUrl(): string
    {
        return Link::raw("vendor/wordless/dist/css/{$this->filename()}");
    }
}
