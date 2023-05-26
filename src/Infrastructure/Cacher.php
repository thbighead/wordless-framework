<?php

namespace Wordless\Infrastructure;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Mounters\Stub\SimpleCacheStubMounter;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

abstract class Cacher
{
    private SimpleCacheStubMounter $simpleCacheStubMounter;

    abstract protected function cacheFilename(): string;

    abstract protected function mountCacheArray(): array;

    /**
     * @throws FailedToCopyStub failed to create cache file.
     * @throws PathNotFoundException 'cache' directory was not found.
     */
    public function cache(): void
    {
        try {
            $cache_file_path = ProjectPath::cache($this->cacheFilename());
            $cached_values = include $cache_file_path;
            $array_to_cache = $this->mountCacheArray();

            if (Arr::isAssociative($array_to_cache)) {
                Arr::recursiveJoin($cached_values, $array_to_cache);
            } else {
                Arr::recursiveJoin($cached_values, ...$array_to_cache);
            }
        } catch (PathNotFoundException $exception) {
            $cache_file_path = ProjectPath::cache() . DIRECTORY_SEPARATOR . $this->cacheFilename();
            $array_to_cache = $this->mountCacheArray();

            if (!Arr::isAssociative($array_to_cache)) {
                $array_to_cache = Arr::recursiveJoin(...$array_to_cache);
            }
        }

        $stubMounterClass = $this->stubMounterClass();
        $this->simpleCacheStubMounter = new $stubMounterClass($cache_file_path);
        $this->simpleCacheStubMounter->setReplaceContentDictionary($array_to_cache)
            ->mountNewFile();
    }

    public function getSimpleCacheStubMounter(): ?SimpleCacheStubMounter
    {
        return $this->simpleCacheStubMounter ?? null;
    }

    protected function stubMounterClass(): string
    {
        return SimpleCacheStubMounter::class;
    }
}
