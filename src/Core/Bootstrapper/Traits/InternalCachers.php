<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use Wordless\Infrastructure\Cacher;

trait InternalCachers
{
    /**
     * @return string[]|Cacher[]
     */
    public function loadProvidedInternalCachers(): array
    {
        $internal_cachers_namespaces = [];

        foreach ($this->loaded_providers as $provider) {
            foreach ($provider->registerInternalCachers() as $internal_cacher_namespace) {
                $internal_cachers_namespaces[$internal_cacher_namespace] = true;
            }
        }

        return array_keys($internal_cachers_namespaces);
    }
}
