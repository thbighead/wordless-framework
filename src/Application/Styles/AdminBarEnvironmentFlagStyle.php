<?php

namespace Wordless\Application\Styles;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Link;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;

class AdminBarEnvironmentFlagStyle extends EnqueueableStyle
{
    /**
     * @return string
     * @throws FormatException
     * @throws DotEnvNotSetException
     */
    protected function filename(): string
    {
        return 'env-flag' . (Environment::isProduction() ? '.min.css' : '.css');
    }

    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    protected function mountFileUrl(): string
    {
        $dist_folder = Environment::isProduction() ? 'dist' : 'assets';

        return Link::raw("vendor/wordless/$dist_folder/css/{$this->filename()}");
    }
}
