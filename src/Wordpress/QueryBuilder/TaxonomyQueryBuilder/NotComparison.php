<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;

use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\ComparisonTaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\NotWhereComparisons;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\WhereOperator;

final class NotComparison extends ComparisonTaxonomyQueryBuilder implements NotWhereComparisons
{
    public function __construct(TaxonomyQueryBuilder $taxonomyQueryBuilder)
    {
        $this->operator = WhereOperator::not;

        parent::__construct($taxonomyQueryBuilder);
    }

    public function notOnlyAvailableInAdminMenu(): NotComparison
    {
        return $this->onlyAvailableInAdminMenu();
    }

    public function notOnlyAvailableInRestApi(): NotComparison
    {
        return $this->onlyAvailableInRestApi();
    }

    public function notOnlyAvailableInTagCloud(): NotComparison
    {
        return $this->onlyAvailableInTagCloud();
    }

    public function notOnlyCustom(): NotComparison
    {
        return $this->onlyCustom();
    }

    public function notOnlyDefault(): NotComparison
    {
        return $this->onlyDefault();
    }

    public function notOnlyHiddenFromAdminMenu(): NotComparison
    {
        return $this->onlyHiddenFromAdminMenu();
    }

    public function notOnlyHiddenFromTagCloud(): NotComparison
    {
        return $this->onlyHiddenFromTagCloud();
    }

    public function notOnlyHiddenFromRestApi(): NotComparison
    {
        return $this->onlyHiddenFromRestApi();
    }

    public function notOnlyPrivate(): NotComparison
    {
        return $this->onlyPrivate();
    }

    public function notOnlyPublic(): NotComparison
    {
        return $this->onlyPublic();
    }

    public function notWhereAdminMenuLabel(string $label): NotComparison
    {
        return $this->whereAdminMenuLabel($label);
    }

    public function notWhereAdminMenuSingularLabel(string $singular_label): NotComparison
    {
        return $this->whereAdminMenuSingularLabel($singular_label);
    }

    public function notWhereAssignPermission(string $capability): NotComparison
    {
        return $this->whereAssignPermission($capability);
    }

    public function notWhereCanBeUsedBy(ObjectType $objectType): NotComparison
    {
        return $this->whereCanBeUsedBy($objectType);
    }

    public function notWhereCanOnlyBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): NotComparison
    {
        array_unshift($objectTypes, $objectType);

        return $this->whereCanOnlyBeUsedBy(...$objectTypes);
    }

    public function notWhereDeletePermission(string $capability): NotComparison
    {
        return $this->whereDeletePermission($capability);
    }

    public function notWhereEditPermission(string $capability): NotComparison
    {
        return $this->whereEditPermission($capability);
    }

    public function notWhereManagePermission(string $capability): NotComparison
    {
        return $this->whereManagePermission($capability);
    }

    public function notWhereName(string $name): NotComparison
    {
        return $this->whereName($name);
    }

    public function notWhereUrlQueryVariable(string $query_variable): NotComparison
    {
        return $this->whereUrlQueryVariable($query_variable);
    }
}
