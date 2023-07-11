<?php

namespace Wordless\Abstractions;

use InvalidArgumentException;
use Wordless\Abstractions\EnqueueableElements\EnqueueableScript;
use Wordless\Abstractions\EnqueueableElements\EnqueueableStyle;
use Wordless\Exceptions\DuplicatedEnqueueableId;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\Str;

abstract class EnqueueableElement
{
    abstract public static function configKey(): string;

    abstract public function enqueue(): void;

    private static array $ids_pool = [
        EnqueueableStyle::class => [],
        EnqueueableScript::class => []
    ];

    protected const CONFIG_FILENAME = 'enqueue';

    protected array $dependencies;
    protected string $id;
    protected string $relative_file_path;
    private string $file_path;
    private ?string $version;

    /**
     * @param string $id
     * @param string $relative_file_path
     * @param array $dependencies
     * @param string|null $version
     * @throws DuplicatedEnqueueableId
     */
    public function __construct(
        string  $id,
        string  $relative_file_path,
        array   $dependencies = [],
        ?string $version = null
    ) {
        $this->setId($id);
        $this->relative_file_path = $relative_file_path;
        $this->dependencies = $dependencies;
        $this->version = $version;
        $this->setFilePath();
    }

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public static function enqueueAll(): void
    {
        $style_mounters_to_queue = Config::tryToGetOrDefault(
            self::CONFIG_FILENAME . '.' . static::configKey(),
            []
        );

        foreach ($style_mounters_to_queue as $style_mounter_class) {
            /** @var EnqueueableMounter $enqueueableStyleMounter */
            $enqueueableStyleMounter = new $style_mounter_class;
            $enqueueableStyleMounter->mountAndEnqueue();
        }
    }

    public function id(): string
    {
        return $this->id;
    }

    protected function filepath(): string
    {
        return $this->file_path;
    }

    /**
     * @param string $id
     * @return void
     * @throws DuplicatedEnqueueableId
     */
    protected function setId(string $id): void
    {
        if (empty($id)) {
            throw new InvalidArgumentException(static::class . ' must have a non-empty id');
        }

        if ($foundEnqueueableClass = static::$ids_pool[static::class][$id] ?? '') {
            throw new DuplicatedEnqueueableId(static::class, $id, $foundEnqueueableClass);
        }

        static::$ids_pool[static::class][$id] = true;
        $this->id = $id;
    }

    /**
     * @return false|string
     */
    protected function version()
    {
        return $this->version ?? false;
    }

    private function setFilePath()
    {
        if (filter_var($this->relative_file_path, FILTER_VALIDATE_URL)) {
            $this->file_path = $this->relative_file_path;

            return;
        }

        $this->file_path = get_stylesheet_directory_uri() . Str::startWith($this->relative_file_path, '/');
    }
}
