<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\ApiController\Traits;

use Wordless\Application\Guessers\ControllerResourceNameGuesser;
use Wordless\Application\Guessers\ControllerVersionGuesser;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToFindCachedKey;
use Wordless\Core\InternalCache;
use Wordless\Core\InternalCache\Exceptions\InternalCacheNotLoaded;

trait Guesser
{
    private ?ControllerResourceNameGuesser $resourceNameGuesser;
    private ?ControllerVersionGuesser $versionGuesser;

    protected function resourceName(): string
    {
        $controller_resource_name_class = static::class;

        try {
            return InternalCache::getValueOrFail(
                "controllers.$controller_resource_name_class.resource_name"
            );
        } catch (FailedToFindCachedKey|InternalCacheNotLoaded $exception) {
            if (!isset($this->resourceNameGuesser)) {
                $this->resourceNameGuesser = new ControllerResourceNameGuesser($controller_resource_name_class);
            }

            return $this->resourceNameGuesser->getValue();
        }
    }

    protected function version(): ?string
    {
        $controller_resource_name_class = static::class;

        try {
            return InternalCache::getValueOrFail("controllers.$controller_resource_name_class.version");
        } catch (FailedToFindCachedKey|InternalCacheNotLoaded $exception) {
            if (!isset($this->resourceNameGuesser)) {
                $this->versionGuesser = new ControllerVersionGuesser($controller_resource_name_class);
            }

            $version_number = $this->versionGuesser->getValue();

            return empty($version_number) ? null : "v$version_number";
        }
    }
}
