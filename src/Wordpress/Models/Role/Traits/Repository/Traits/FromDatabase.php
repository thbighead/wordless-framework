<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Role\Traits\Repository\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToCreateRole;
use Wordless\Wordpress\Models\Role\Traits\Repository\Traits\FromDatabase\Traits\Sync;
use WP_Role;

trait FromDatabase
{
    use Sync;

    /**
     * @param string $name
     * @param string $capability
     * @param string ...$capabilities
     * @return static
     * @throws FailedToCreateRole
     */
    public static function create(string $name, string $capability, string ...$capabilities): static
    {
        $capabilities = self::mountCapabilities($capability, ...$capabilities);
        $newRole = wp_roles()->add_role($slug_key = Str::slugCase($name), $name, $capabilities);

        if (!($newRole instanceof WP_Role)) {
            throw new FailedToCreateRole($slug_key, $name, $capabilities);
        }

        return new static($newRole);
    }

    public static function delete(string $role): void
    {
        wp_roles()->remove_role($role);
    }

    /**
     * @param string $capability
     * @param string ...$capabilities
     * @return array<string, true>
     */
    private static function mountCapabilities(string $capability, string ...$capabilities): array
    {
        $mounted_capabilities = [];

        foreach (Arr::prepend($capabilities, $capability) as $capability) {
            $mounted_capabilities[$capability] = true;
        }

        return $mounted_capabilities;
    }

    public function addCapability(string $capability): void
    {
        $this->add_cap($capability);
    }

    public function removeCapability(string $capability): void
    {
        $this->remove_cap($capability);
    }

    /**
     * @param bool[] $capabilities
     * @return void
     */
    public function syncCapabilities(array $capabilities): void
    {
        foreach ($capabilities as $capability => $can) {
            $can ? $this->addCapability($capability) : $this->removeCapability($capability);
        }
    }
}
