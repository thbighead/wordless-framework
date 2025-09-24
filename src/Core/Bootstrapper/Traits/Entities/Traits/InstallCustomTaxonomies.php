<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Entities\Traits;

use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Exceptions\CustomTaxonomyRegistrationFailed;

trait InstallCustomTaxonomies
{
    private array $loaded_custom_taxonomies = [];

    /**
     * @return string[]|CustomTaxonomy[]
     */
    private function getLoadedCustomTaxonomies(): array
    {
        return array_keys($this->loaded_custom_taxonomies);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function loadCustomTaxonomies(Provider $provider): static
    {
        foreach ($provider->registerTaxonomies() as $custom_taxonomy_namespace) {
            $this->loaded_custom_taxonomies[$custom_taxonomy_namespace] = true;
        }

        return $this;
    }

    /**
     * @return $this
     * @throws CustomTaxonomyRegistrationFailed
     */
    private function resolveCustomTaxonomies(): static
    {
        foreach ($this->getLoadedCustomTaxonomies() as $custom_taxonomy_namespace) {
            $custom_taxonomy_namespace::register();
        }

        return $this;
    }
}
