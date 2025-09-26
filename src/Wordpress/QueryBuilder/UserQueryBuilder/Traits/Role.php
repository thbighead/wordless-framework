<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\Models\Role as RoleModel;
use Wordless\Wordpress\Models\Role\Enums\StandardRole;

trait Role
{
    public function whereRole(RoleModel|StandardRole|string $role): static
    {
        $this->arguments['role'][] = $this->extractRoleReference($role);

        return $this;
    }

    public function whereRoleIn(RoleModel|StandardRole|string $role, RoleModel|StandardRole|string ...$roles): static
    {
        $this->arguments['role__in'] = $this->extractRolesReferences(Arr::prepend($roles, $role));

        return $this;
    }

    public function whereRoleNotIn(RoleModel|StandardRole|string $role, RoleModel|StandardRole|string ...$roles): static
    {
        $this->arguments['role__not_in'] = $this->extractRolesReferences(Arr::prepend($roles, $role));

        return $this;
    }

    private function extractRoleReference(RoleModel|StandardRole|string $role): string
    {
        return $role->value ?? $role->name ?? $role;
    }

    /**
     * @param array<int, RoleModel|StandardRole|string> $roles
     * @return string[]
     */
    private function extractRolesReferences(array $roles): array
    {
        return array_map([$this, 'extractRoleReference'], $roles);
    }
}
