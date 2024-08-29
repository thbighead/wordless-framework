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
    protected static function dependencies(): array
    {
        return parent::dependencies();
    }

    protected static function media(): MediaOption
    {
        return MediaOption::all;
    }

    public function enqueue(): void
    {
        wp_enqueue_style(
            $this->getId(),
            $this->getFileUrl(),
            $this->getDependencies(),
            $this->getVersion(),
            $this->getMedia()
        );
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

    private function getMedia(): string
    {
        return static::media()->name;
    }
}
