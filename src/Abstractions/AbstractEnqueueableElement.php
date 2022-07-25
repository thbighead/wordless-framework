<?php

namespace Wordless\Abstractions;

use Wordless\Exceptions\DuplicatedEnqueuableId;

abstract class AbstractEnqueueableElement
{
    abstract public static function enqueueAll(): void;

    abstract public function enqueue(): void;

    abstract protected function filepath(): string;

    private static array $ids_pool = [];

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
        if ($foundEnqueuableClass = static::$ids_pool[$id]) {
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