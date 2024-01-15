<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;

use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\AndWhereComparisons;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\ComparisonTaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\WhereOperator;

final class AndComparison extends ComparisonTaxonomyQueryBuilder implements AndWhereComparisons
{
    public function __construct(TaxonomyQueryBuilder $taxonomyQueryBuilder)
    {
        $this->operator = WhereOperator::and;

        parent::__construct($taxonomyQueryBuilder);
    }

    public function andOnlyAvailableInAdminMenu(): AndComparison
    {
        return $this->onlyAvailableInAdminMenu();
    }

    public function andOnlyAvailableInRestApi(): AndComparison
    {
        return $this->onlyAvailableInRestApi();
    }

    public function andOnlyAvailableInTagCloud(): AndComparison
    {
        return $this->onlyAvailableInTagCloud();
    }

    public function andOnlyCustom(): AndComparison
    {
        return $this->onlyCustom();
    }

    public function andOnlyDefault(): AndComparison
    {
        return $this->onlyDefault();
    }

    public function andOnlyHiddenFromAdminMenu(): AndComparison
    {
        return $this->onlyHiddenFromAdminMenu();
    }

    public function andOnlyHiddenFromTagCloud(): AndComparison
    {
        return $this->onlyHiddenFromTagCloud();
    }

    public function andOnlyHiddenFromRestApi(): AndComparison
    {
        return $this->onlyHiddenFromRestApi();
    }

    public function andOnlyPrivate(): AndComparison
    {
        return $this->onlyPrivate();
    }

    public function andOnlyPublic(): AndComparison
    {
        return $this->onlyPublic();
    }

    public function andWhereAdminMenuLabel(string $label): AndComparison
    {
        return $this->whereAdminMenuLabel($label);
    }

    public function andWhereAdminMenuSingularLabel(string $singular_label): AndComparison
    {
        return $this->whereAdminMenuSingularLabel($singular_label);
    }

    public function andWhereAssignPermission(string $capability): AndComparison
    {
        return $this->whereAssignPermission($capability);
    }

    public function andWhereCanBeUsedBy(ObjectType $objectType): AndComparison
    {
        return $this->whereCanBeUsedBy($objectType);
    }

    public function andWhereCanOnlyBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): AndComparison
    {
        array_unshift($objectTypes, $objectType);

        return $this->whereCanOnlyBeUsedBy(...$objectTypes);
    }

    public function andWhereDeletePermission(string $capability): AndComparison
    {
        return $this->whereDeletePermission($capability);
    }

    public function andWhereEditPermission(string $capability): AndComparison
    {
        return $this->whereEditPermission($capability);
    }

    public function andWhereManagePermission(string $capability): AndComparison
    {
        return $this->whereManagePermission($capability);
    }

    public function andWhereName(string $name): AndComparison
    {
        return $this->whereName($name);
    }

    public function andWhereUrlQueryVariable(string $query_variable): AndComparison
    {
        return $this->whereUrlQueryVariable($query_variable);
    }
}
