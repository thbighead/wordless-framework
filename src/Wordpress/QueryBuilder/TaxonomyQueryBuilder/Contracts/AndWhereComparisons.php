<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts;

use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\AndComparison;

interface AndWhereComparisons
{
    public function andOnlyAvailableInAdminMenu(): AndComparison;

    public function andOnlyAvailableInRestApi(): AndComparison;

    public function andOnlyAvailableInTagCloud(): AndComparison;

    public function andOnlyCustom(): AndComparison;

    public function andOnlyDefault(): AndComparison;

    public function andOnlyHiddenFromAdminMenu(): AndComparison;

    public function andOnlyHiddenFromTagCloud(): AndComparison;

    public function andOnlyHiddenFromRestApi(): AndComparison;

    public function andOnlyPrivate(): AndComparison;

    public function andOnlyPublic(): AndComparison;

    public function andWhereAdminMenuLabel(string $label): AndComparison;

    public function andWhereAdminMenuSingularLabel(string $singular_label): AndComparison;

    public function andWhereAssignPermission(string $capability): AndComparison;

    public function andWhereCanBeUsedBy(ObjectType $objectType): AndComparison;

    public function andWhereCanOnlyBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): AndComparison;

    public function andWhereDeletePermission(string $capability): AndComparison;

    public function andWhereEditPermission(string $capability): AndComparison;

    public function andWhereManagePermission(string $capability): AndComparison;

    public function andWhereName(string $name): AndComparison;

    public function andWhereUrlQueryVariable(string $query_variable): AndComparison;
}
