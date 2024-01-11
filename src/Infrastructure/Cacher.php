<?php declare(strict_types=1);

namespace Wordless\Infrastructure;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Mounters\Stub\SimpleCacheStubMounter;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

abstract class Cacher
{
    abstract protected function cacheFilename(): string;

    abstract protected function mountCacheArray(): array;

    private SimpleCacheStubMounter $simpleCacheStubMounter;

    /**
     * @return void
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     */
    final public static function generate(): void
    {
        (new static)->cache();
    }

    /**
     * @return void
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     */
    private function cache(): void
    {
        $array_to_cache = $this->mountCacheArray();

        if (!Arr::isAssociative($array_to_cache)) {
            $array_to_cache = Arr::recursiveJoin(...$array_to_cache);
        }

        $this->getSimpleCacheStubMounter()
            ->setReplaceContentDictionary($array_to_cache)
            ->mountNewFile();
    }

    /**
     * @return SimpleCacheStubMounter
     * @throws PathNotFoundException
     */
    private function getSimpleCacheStubMounter(): SimpleCacheStubMounter
    {
        if (isset($this->simpleCacheStubMounter)) {
            return $this->simpleCacheStubMounter;
        }

        $cache_file_path = ProjectPath::cache() . DIRECTORY_SEPARATOR . $this->cacheFilename();
        $stubMounterClass = $this->stubMounterClass();

        return $this->simpleCacheStubMounter = new $stubMounterClass($cache_file_path);
    }

    private function stubMounterClass(): string
    {
        return SimpleCacheStubMounter::class;
    }
}
