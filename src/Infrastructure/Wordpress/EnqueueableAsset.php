<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use InvalidArgumentException;
use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;

abstract class EnqueueableAsset
{
    abstract protected static function id(): string;

    abstract protected static function relativeFilepath(): string;

    abstract public function enqueue(): void;

    private static array $ids_pool = [
        EnqueueableStyle::class => [],
        EnqueueableScript::class => []
    ];

    /** @var string[] $dependencies */
    private array $dependencies = [];
    private string $filepath;
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

    protected static function version(): ?string
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

    protected function getFilepath(): string
    {
        return $this->filepath;
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
        return static::version() ?? false;
    }

    /**
     * @throws DuplicatedEnqueueableId
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     */
    private function __construct()
    {
        $this->setId()
            ->setFilepath()
            ->setDependencies();
    }

    private function setDependencies(): void
    {
        foreach (static::dependencies() as $enqueueable_asset_namespace) {
            $this->dependencies[] = $enqueueable_asset_namespace::id();
        }
    }

    /**
     * @return $this
     * @throws PathNotFoundException
     */
    private function setFilepath(): static
    {
        $this->filepath = Link::themePublic(static::relativeFilepath());

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
