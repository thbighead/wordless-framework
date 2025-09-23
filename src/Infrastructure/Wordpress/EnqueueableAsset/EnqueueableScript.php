<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\Link\Traits\Internal\Exceptions\FailedToGuessBaseAssetsUri;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset;

abstract class EnqueueableScript extends EnqueueableAsset
{
    /**
     * @return string[]|EnqueueableScript[]
     */
    protected function dependencies(): array
    {
        return parent::dependencies();
    }

    /**
     * @return string
     * @throws FailedToGuessBaseAssetsUri
     */
    protected function mountFileUrl(): string
    {
        return Link::js($this->filename());
    }

    final protected function callWpEnqueueFunction(): void
    {
        wp_enqueue_script(
            $this->getId(),
            $this->getFileUrl(),
            $this->getDependenciesIds(),
            $this->getVersion(),
            false
        );
    }
}
