<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts;

use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\NotComparison;

interface NotWhereComparisons
{
    public function notOnlyAvailableInAdminMenu(): NotComparison;

    public function notOnlyAvailableInRestApi(): NotComparison;

    public function notOnlyAvailableInTagCloud(): NotComparison;

    public function notOnlyCustom(): NotComparison;

    public function notOnlyDefault(): NotComparison;

    public function notOnlyHiddenFromAdminMenu(): NotComparison;

    public function notOnlyHiddenFromTagCloud(): NotComparison;

    public function notOnlyHiddenFromRestApi(): NotComparison;

    public function notOnlyPrivate(): NotComparison;

    public function notOnlyPublic(): NotComparison;

    public function notWhereAdminMenuLabel(string $label): NotComparison;

    public function notWhereAdminMenuSingularLabel(string $singular_label): NotComparison;

    public function notWhereAssignPermission(string $capability): NotComparison;

    public function notWhereCanBeUsedBy(ObjectType $objectType): NotComparison;

    public function notWhereCanOnlyBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): NotComparison;

    public function notWhereDeletePermission(string $capability): NotComparison;

    public function notWhereEditPermission(string $capability): NotComparison;

    public function notWhereManagePermission(string $capability): NotComparison;

    public function notWhereName(string $name): NotComparison;

    public function notWhereUrlQueryVariable(string $query_variable): NotComparison;
}
