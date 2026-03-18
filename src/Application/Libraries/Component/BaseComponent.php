<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Component;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Enums\StandardContext;

abstract class BaseComponent
{
    abstract protected function script(): ?EnqueueableScript;

    abstract protected function style(): ?EnqueueableStyle;

    abstract public function html(): string;

    private static array $already_loaded_assets = [];

    public function __construct(readonly private StandardContext $context = StandardContext::no_context)
    {
        $this->loadAssets();
    }

    private function enqueueAsset(?EnqueueableAsset $asset): void
    {
        if (!is_null($asset) && !isset(self::$already_loaded_assets[$asset::class])) {
            $asset->enqueue($this->context);
            self::$already_loaded_assets[$asset::class] = $asset::class;
        }
    }

    private function loadAssets(): void
    {
        $this->loadScript()->loadStyle();
    }

    private function loadScript(): static
    {
        $this->enqueueAsset($this->script());

        return $this;
    }

    private function loadStyle(): void
    {
        $this->enqueueAsset($this->style());
    }
}
