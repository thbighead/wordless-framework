<?php

namespace Wordless\Contracts\Controller;

use Wordless\Abstractions\Guessers\ControllerResourceNameGuesser;
use Wordless\Abstractions\Guessers\ControllerVersionGuesser;
use Wordless\Abstractions\InternalCache;
use Wordless\Exceptions\FailedToFindCachedKey;
use Wordless\Exceptions\InternalCacheNotLoaded;

trait Guesser
{
    private ?ControllerResourceNameGuesser $resourceNameGuesser;
    private ?ControllerVersionGuesser $versionGuesser;

    /**
     * @return string
     * @throws InternalCacheNotLoaded
     */
    protected function resourceName(): string
    {
        $controller_resource_name_class = static::class;

        try {
            return InternalCache::getValueOrFail(
                "controllers.$controller_resource_name_class.resource_name"
            );
        } catch (FailedToFindCachedKey $exception) {
            if (!isset($this->resourceNameGuesser)) {
                $this->resourceNameGuesser = new ControllerResourceNameGuesser($controller_resource_name_class);
            }

            return $this->resourceNameGuesser->getValue();
        }
    }

    /**
     * @return string|null
     * @throws InternalCacheNotLoaded
     */
    protected function version(): ?string
    {
        $controller_resource_name_class = static::class;

        try {
            return InternalCache::getValueOrFail("controllers.$controller_resource_name_class.version");
        } catch (FailedToFindCachedKey $exception) {
            if (!isset($this->resourceNameGuesser)) {
                $this->versionGuesser = new ControllerVersionGuesser($controller_resource_name_class);
            }

            $version_number = $this->versionGuesser->getValue();

            return empty($version_number) ? null : "v$version_number";
        }
    }
}