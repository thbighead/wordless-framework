<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle\Enums\MediaOption;

abstract class EnqueueableStyle extends EnqueueableAsset
{
    /**
     * @return string[]|EnqueueableStyle[]
     */
    protected function dependencies(): array
    {
        return parent::dependencies();
    }

    protected function media(): MediaOption
    {
        return MediaOption::all;
    }

    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws PathNotFoundException
     */
    protected function mountFileUrl(): string
    {
        return Link::css($this->filename());
    }

    final protected function callWpEnqueueFunction(): void
    {
        wp_enqueue_style(
            $this->getId(),
            $this->getFileUrl(),
            $this->getDependenciesIds(),
            $this->getVersion(),
            $this->getMedia()
        );
    }

    private function getMedia(): string
    {
        return $this->media()->name;
    }
}
