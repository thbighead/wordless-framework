<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Provider\Traits;

use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Wordpress\Listener;

trait ListenersRegistration
{
    /**
     * @return string[]|Listener[]
     */
    public function registerListeners(): array
    {
        return [];
    }

    /**
     * @return RemoveHookDTO[]
     */
    public function unregisterActionListeners(): array
    {
        return [];
    }

    /**
     * @return RemoveHookDTO[]
     */
    public function unregisterFilterListeners(): array
    {
        return [];
    }
}
