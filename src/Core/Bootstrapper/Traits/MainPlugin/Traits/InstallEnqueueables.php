<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\MainPlugin\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;

trait InstallEnqueueables
{
    /** @var EnqueueableScript[] $loaded_enqueueable_scripts */
    private array $loaded_enqueueable_scripts = [];
    /** @var EnqueueableStyle[] $loaded_enqueueable_styles */
    private array $loaded_enqueueable_styles = [];

    private function loadEnqueueableScripts(Provider $provider): static
    {
        foreach ($provider->registerEnqueueableScripts() as $enqueueable_script_namespace) {
            $this->loaded_enqueueable_scripts[$enqueueable_script_namespace] = true;
        }

        return $this;
    }

    private function loadEnqueueableStyles(Provider $provider): static
    {
        foreach ($provider->registerEnqueueableStyles() as $enqueueable_style_namespace) {
            $this->loaded_enqueueable_styles[$enqueueable_style_namespace] = true;
        }

        return $this;
    }

    private function loadEnqueueableAssets(Provider $provider): static
    {
        return $this->loadEnqueueableScripts($provider)
            ->loadEnqueueableStyles($provider);
    }

    /**
     * @return $this
     * @throws DuplicatedEnqueueableId
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     */
    private function resolveEnqueues(): static
    {
        return $this->resolveScriptEnqueues()
            ->resolveStyleEnqueues();
    }

    /**
     * @return $this
     * @throws DuplicatedEnqueueableId
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     */
    private function resolveScriptEnqueues(): static
    {
        /** @var EnqueueableScript $enqueueable_script_namespace */
        foreach ($this->loaded_enqueueable_scripts as $enqueueable_script_namespace => $can_enqueue) {
            $enqueueable_script_namespace::make()->enqueue();
        }

        return $this;
    }

    /**
     * @return $this
     * @throws DuplicatedEnqueueableId
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     */
    private function resolveStyleEnqueues(): static
    {
        /** @var EnqueueableStyle $enqueueable_style_namespace */
        foreach ($this->loaded_enqueueable_styles as $enqueueable_style_namespace => $can_enqueue) {
            $enqueueable_style_namespace::make()->enqueue();
        }

        return $this;
    }
}
