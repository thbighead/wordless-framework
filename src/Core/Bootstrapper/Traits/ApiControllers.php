<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use Wordless\Infrastructure\Wordpress\ApiController;

trait ApiControllers
{
    /**
     * @return string[]|ApiController[]
     */
    public function loadProvidedApiControllers(): array
    {
        $api_controllers_namespaces = [];

        foreach ($this->loaded_providers as $provider) {
            foreach ($provider->registerApiControllers() as $api_controller_namespace) {
                $api_controllers_namespaces[$api_controller_namespace] = true;
            }
        }

        return array_keys($api_controllers_namespaces);
    }
}
