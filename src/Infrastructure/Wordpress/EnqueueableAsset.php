<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use InvalidArgumentException;
use Wordless\Application\Guessers\EnqueueableAssetIdGuesser;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Contracts\Context;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Enums\StandardContext;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\EmptyEnqueueableId;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\InvalidTypeEnqueueableClass;

abstract class EnqueueableAsset
{
    abstract public function loadOnAdmin(): bool;

    abstract public function loadOnFrontend(): bool;

    abstract protected function callWpEnqueueFunction(): void;

    abstract protected function filename(): string;

    abstract protected function mountFileUrl(): string;

    private static array $already_enqueued = [];
    private static array $ids_pool = [
        EnqueueableStyle::class => [],
        EnqueueableScript::class => [],
    ];

    /** @var array<string|static, string> $dependencies_ids */
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

    final public function alreadyEnqueued(Context $context = StandardContext::no_context): bool
    {
        return Arr::hasKey(self::$already_enqueued[static::class] ?? [], $context->name);
    }

    final public function enqueue(Context $context = StandardContext::no_context): bool
    {
        if ($this->notEnqueuedYet($context)) {
            $this->callWpEnqueueFunction();

            return self::$already_enqueued[static::class][$context->name] = true;
        }

        return false;
    }

    /**
     * @param Context $context
     * @return void
     * @throws DuplicatedEnqueueableId
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     */
    final public function enqueueDependenciesRecursively(Context $context = StandardContext::no_context): void
    {
        foreach ($this->dependencies_ids as $dependency_class_namespace => $dependency_id) {
            if (($dependency = $dependency_class_namespace::make())->enqueue($context)) {
                $dependency->enqueueDependenciesRecursively($context);
            }
        }
    }

    final public function notEnqueuedYet(Context $context = StandardContext::no_context): bool
    {
        return !$this->alreadyEnqueued($context);
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
        return array_values($this->dependencies_ids);
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
            $this->dependencies_ids[$enqueueable_asset_namespace] = $enqueueable_asset_namespace::id();
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

        $found_enqueueable_class_namespace = self::$ids_pool[$type_enqueueable_class_namespace][$id] ?? false;

        if ($found_enqueueable_class_namespace && $found_enqueueable_class_namespace !== static::class) {
            throw new DuplicatedEnqueueableId(
                $type_enqueueable_class_namespace,
                $id,
                static::class,
                $found_enqueueable_class_namespace
            );
        }

        self::$ids_pool[$type_enqueueable_class_namespace][$id] = static::class;
        $this->id = $id;

        return $this;
    }
}
