<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Entities\Traits;

use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\CustomPostStatus;
use Wordless\Infrastructure\Wordpress\CustomPostStatus\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostStatusKey;

trait InstallCustomPostStatuses
{
    private array $loaded_custom_post_statuses = [];

    /**
     * @return string[]|CustomPostStatus[]
     */
    private function getLoadedCustomPostStatuses(): array
    {
        return array_keys($this->loaded_custom_post_statuses);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function loadCustomPostStatuses(Provider $provider): static
    {
        foreach ($provider->registerPostStatuses() as $custom_post_type_namespace) {
            $this->loaded_custom_post_statuses[$custom_post_type_namespace] = true;
        }

        return $this;
    }

    /**
     * @return $this
     * @throws ReservedCustomPostStatusKey
     */
    private function resolveCustomPostStatuses(): static
    {
        foreach ($this->getLoadedCustomPostStatuses() as $custom_post_status_namespace) {
            $custom_post_status_namespace::register();
        }

        return $this;
    }
}
