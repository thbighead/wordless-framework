<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use InvalidArgumentException;
use Wordless\Application\Guessers\EnqueueableAssetIdGuesser;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;

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

    /** @var string[] $dependencies */
    private array $dependencies = [];
    private string $file_url;
    private string $id;

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
    protected static function dependencies(): array
    {
        return [];
    }

    protected static function id(): string
    {
        return (new EnqueueableAssetIdGuesser(static::class))->getValue();
    }

    protected function version(): ?string
    {
        return null;
    }

    /**
     * @return string[]
     */
    protected function getDependencies(): array
    {
        return $this->dependencies;
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
     * @throws InvalidArgumentException
     */
    private function __construct()
    {
        $this->setId()
            ->setFileUrl()
            ->setDependencies();
    }

    private function setDependencies(): void
    {
        foreach (static::dependencies() as $enqueueable_asset_namespace) {
            $this->dependencies[] = $enqueueable_asset_namespace::id();
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
     * @throws InvalidArgumentException
     */
    private function setId(): static
    {
        $id = static::id();

        if (empty($id)) {
            throw new InvalidArgumentException(static::class . ' must have a non-empty id');
        }

        if ($foundEnqueueableClass = static::$ids_pool[static::class][$id] ?? '') {
            throw new DuplicatedEnqueueableId(static::class, $id, $foundEnqueueableClass);
        }

        static::$ids_pool[static::class][$id] = true;
        $this->id = $id;

        return $this;
    }
}
