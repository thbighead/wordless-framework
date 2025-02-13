<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use InvalidArgumentException;
use Wordless\Application\Guessers\EnqueueableAssetIdGuesser;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\EmptyEnqueueableId;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\InvalidTypeEnqueueableClass;

abstract class EnqueueableAsset
{
    abstract public function enqueue(): void;

    abstract public function loadOnAdmin(): bool;

    abstract public function loadOnFrontend(): bool;

    abstract protected function filename(): string;

    abstract protected function mountFileUrl(): string;

    private static array $ids_pool = [
        EnqueueableStyle::class => [],
        EnqueueableScript::class => []
    ];

    /** @var string[] $dependencies_ids */
    private array $dependencies_ids = [];
    private string $file_url;
    private string $id;

    public static function id(): string
    {
        return (new EnqueueableAssetIdGuesser(static::class))->getValue();
    }

    /**
     * @return static
     * @throws DuplicatedEnqueueableId
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     * @noinspection PhpDocRedundantThrowsInspection
     */
    public static function make(): static
    {
        return new static;
    }

    /**
     * @return string[]|EnqueueableAsset[]
     */
    protected function dependencies(): array
    {
        return [];
    }

    protected function version(): ?string
    {
        return null;
    }

    /**
     * @return string[]
     */
    protected function getDependenciesIds(): array
    {
        return $this->dependencies_ids;
    }

    protected function getFileUrl(): string
    {
        return $this->file_url;
    }

    protected function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string|false
     */
    protected function getVersion(): string|bool
    {
        return $this->version() ?? false;
    }

    /**
     * @throws DuplicatedEnqueueableId
     * @throws EmptyEnqueueableId
     * @throws InvalidTypeEnqueueableClass
     */
    private function __construct()
    {
        $this->setId()
            ->setFileUrl()
            ->setDependenciesIds();
    }

    private function setDependenciesIds(): void
    {
        foreach ($this->dependencies() as $enqueueable_asset_namespace) {
            $this->dependencies_ids[] = $enqueueable_asset_namespace::id();
        }
    }

    private function setFileUrl(): static
    {
        $this->file_url = $this->mountFileUrl();

        return $this;
    }

    /**
     * @return $this
     * @throws DuplicatedEnqueueableId
     * @throws EmptyEnqueueableId
     * @throws InvalidTypeEnqueueableClass
     */
    private function setId(): static
    {
        $id = static::id();

        if (empty($id)) {
            throw new EmptyEnqueueableId(static::class);
        }

        $type_enqueueable_class_namespace = match (true) {
            $this instanceof EnqueueableScript => EnqueueableScript::class,
            $this instanceof EnqueueableStyle => EnqueueableStyle::class,
            default => throw new InvalidTypeEnqueueableClass($this::class),
        };

        if (self::$ids_pool[$type_enqueueable_class_namespace][$id] ?? false) {
            throw new DuplicatedEnqueueableId(
                $type_enqueueable_class_namespace,
                $id,
                static::class
            );
        }

        self::$ids_pool[$type_enqueueable_class_namespace][$id] = true;
        $this->id = $id;

        return $this;
    }
}
