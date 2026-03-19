<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Entities\Traits;

use RuntimeException;
use Wordless\Core\Bootstrapper\Traits\Entities\Traits\InstallCustomPostTypes\Exceptions\FailedToResolveCustomPostTypeRegistrar;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\Registrar\CustomPostRegistrar;
use Wordless\Infrastructure\Wordpress\Registrar\CustomPostRegistrar\Exceptions\CustomPostTypeRegistrationFailed;
use Wordless\Infrastructure\Wordpress\Registrar\CustomPostRegistrar\Traits\Validation\Exceptions\InvalidCustomPostTypeKeyFormat;
use Wordless\Infrastructure\Wordpress\Registrar\CustomPostRegistrar\Traits\Validation\Exceptions\ReservedCustomPostTypeKeyFormat;

trait InstallCustomPostTypes
{
    private array $loaded_custom_post_types = [];

    /**
     * @return string[]|CustomPostRegistrar[]
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
     * @throws FailedToResolveCustomPostTypeRegistrar
     * @throws RuntimeException
     */
    private function resolveCustomPostTypes(): static
    {
        foreach ($this->getLoadedCustomPostTypes() as $custom_post_type_namespace) {
            try {
                $custom_post_type_namespace::register();
            } catch (CustomPostTypeRegistrationFailed
            |InvalidCustomPostTypeKeyFormat
            |ReservedCustomPostTypeKeyFormat $exception) {
                throw new FailedToResolveCustomPostTypeRegistrar($custom_post_type_namespace, $exception);
            }
        }

        return $this;
    }
}
