<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts;

use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\OrComparison;

interface OrWhereComparisons
{
    public function orOnlyAvailableInAdminMenu(): OrComparison;

    public function orOnlyAvailableInRestApi(): OrComparison;

    public function orOnlyAvailableInTagCloud(): OrComparison;

    public function orOnlyCustom(): OrComparison;

    public function orOnlyDefault(): OrComparison;

    public function orOnlyHiddenFromAdminMenu(): OrComparison;

    public function orOnlyHiddenFromTagCloud(): OrComparison;

    public function orOnlyHiddenFromRestApi(): OrComparison;

    public function orOnlyPrivate(): OrComparison;

    public function orOnlyPublic(): OrComparison;

    public function orWhereAdminMenuLabel(string $label): OrComparison;

    public function orWhereAdminMenuSingularLabel(string $singular_label): OrComparison;

    public function orWhereAssignPermission(string $capability): OrComparison;

    public function orWhereCanBeUsedBy(ObjectType $objectType): OrComparison;

    public function orWhereCanOnlyBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): OrComparison;

    public function orWhereDeletePermission(string $capability): OrComparison;

    public function orWhereEditPermission(string $capability): OrComparison;

    public function orWhereManagePermission(string $capability): OrComparison;

    public function orWhereName(string $name): OrComparison;

    public function orWhereUrlQueryVariable(string $query_variable): OrComparison;
}
