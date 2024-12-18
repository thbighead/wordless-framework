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
    private static bool $already_enqueued = false;
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
     * @param bool $on_admin
     * @return $this
     * @throws DuplicatedEnqueueableId
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     */
    private function resolveEnqueues(bool $on_admin): static
    {
        if (!self::$already_enqueued) {
            $this->resolveScriptEnqueues($on_admin)
                ->resolveStyleEnqueues($on_admin);

            self::$already_enqueued = true;
        }

        return $this;
    }

    /**
     * @param bool $loading_on_admin
     * @return $this
     * @throws DuplicatedEnqueueableId
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     */
    private function resolveScriptEnqueues(bool $loading_on_admin): static
    {
        /** @var EnqueueableScript $enqueueable_script_namespace */
        foreach ($this->loaded_enqueueable_scripts as $enqueueable_script_namespace => $can_enqueue) {
            $enqueuableScript = $enqueueable_script_namespace::make();

            if (
                ($loading_on_admin && $enqueuableScript->loadOnAdmin())
                || (!$loading_on_admin && $enqueuableScript->loadOnFrontend())
            ) {
                $enqueuableScript->enqueue();
            }
        }

        return $this;
    }

    /**
     * @param bool $loading_on_admin
     * @return $this
     * @throws DuplicatedEnqueueableId
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     */
    private function resolveStyleEnqueues(bool $loading_on_admin): static
    {
        /** @var EnqueueableStyle $enqueueable_style_namespace */
        foreach ($this->loaded_enqueueable_styles as $enqueueable_style_namespace => $can_enqueue) {
            $enqueuableStyle = $enqueueable_style_namespace::make();

            if (
                ($loading_on_admin && $enqueuableStyle->loadOnAdmin())
                || (!$loading_on_admin && $enqueuableStyle->loadOnFrontend())
            ) {
                $enqueuableStyle->enqueue();
            }
        }

        return $this;
    }
}
