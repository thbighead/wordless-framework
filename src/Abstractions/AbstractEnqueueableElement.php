<?php

namespace Wordless\Abstractions;

use InvalidArgumentException;
use Wordless\Exceptions\DuplicatedEnqueuableId;
use Wordless\Exceptions\InternalCacheNotLoaded;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

abstract class AbstractEnqueueableElement
{
    abstract public static function configKey(): string;

    abstract public function enqueue(): void;

    abstract protected function filepath(): string;

    private static array $ids_pool = [];

    protected const CONFIG_FILENAME = 'enqueue.php';

    protected array $dependencies;
    protected string $id;
    protected string $relative_file_path;
    private ?string $version;

    /**
     * @param string $id
     * @param string $relative_file_path
     * @param array $dependencies
     * @param string|null $version
     * @throws DuplicatedEnqueuableId
     */
    public function __construct(
        string  $id,
        string  $relative_file_path,
        array   $dependencies = [],
        ?string $version = null
    )
    {
        $this->setId($id);
        $this->relative_file_path = $relative_file_path;
        $this->dependencies = $dependencies;
        $this->version = $version;
    }

    /**
     * @return void
     * @throws InternalCacheNotLoaded
     * @throws PathNotFoundException
     */
    public static function enqueueAll(): void
    {
        $style_mounters_to_queue = (
            include ProjectPath::config(self::CONFIG_FILENAME)
            )[static::configKey()] ?? [];

        foreach ($style_mounters_to_queue as $style_mounter_class) {
            /** @var AbstractEnqueueableMounter $enqueueableStyleMounter */
            $enqueueableStyleMounter = new $style_mounter_class;
            $enqueueableStyleMounter->mountAndEnqueue();
        }
    }

    public function id(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return void
     * @throws DuplicatedEnqueuableId
     */
    protected function setId(string $id): void
    {
        if (empty($id)) {
            throw new InvalidArgumentException(static::class . ' must have a non-empty id');
        }

        if ($foundEnqueuableClass = static::$ids_pool[$id] ?? '') {
            throw new DuplicatedEnqueuableId(static::class, $id, $foundEnqueuableClass);
        }

        static::$ids_pool[$id] = true;
        $this->id = $id;
    }

    /**
     * @return false|string
     */
    protected function version()
    {
        return $this->version ?? false;
    }
}