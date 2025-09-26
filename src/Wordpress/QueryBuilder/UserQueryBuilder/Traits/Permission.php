<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits;

trait Permission
{
    public function wherePermission(string $permission): static
    {
        return $this->whereCapability($permission);
    }

    public function wherePermissionIn(string $permission, string ...$permissions): static
    {
        return $this->whereCapabilityIn($permission, ...$permissions);
    }

    public function wherePermissionNotIn(string $permission, string ...$permissions): static
    {
        return $this->whereCapabilityNotIn($permission, ...$permissions);
    }
}
