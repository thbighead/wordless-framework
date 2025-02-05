<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Entities\Traits;

use InvalidArgumentException;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions\CustomPostTypeRegistrationFailed;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKey;

trait InstallCustomPostTypes
{
    private array $loaded_custom_post_types = [];

    /**
     * @return string[]|CustomPost[]
     */
    private function getLoadedCustomPostTypes(): array
    {
        return array_keys($this->loaded_custom_post_types);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function loadCustomPostTypes(Provider $provider): static
    {
        foreach ($provider->registerPostTypes() as $custom_post_type_namespace) {
            $this->loaded_custom_post_types[$custom_post_type_namespace] = true;
        }

        return $this;
    }

    /**
     * @return $this
     * @throws CustomPostTypeRegistrationFailed
     * @throws InvalidCustomPostTypeKey
     * @throws ReservedCustomPostTypeKey
     * @throws InvalidArgumentException
     */
    private function resolveCustomPostTypes(): static
    {
        foreach ($this->getLoadedCustomPostTypes() as $custom_post_type_namespace) {
            $custom_post_type_namespace::register();
        }

        return $this;
    }
}
