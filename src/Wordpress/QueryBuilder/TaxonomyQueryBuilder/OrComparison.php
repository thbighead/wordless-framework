<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;

use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\OrWhereComparisons;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\WhereOperator;

final class OrComparison extends BaseTaxonomyQueryBuilder implements OrWhereComparisons
{
    public function __construct(TaxonomyQueryBuilder $taxonomyQueryBuilder)
    {
        $this->format = $taxonomyQueryBuilder->getResultFormat();
        $this->operator = WhereOperator::or;
    }

    public function orOnlyAvailableInAdminMenu(): OrComparison
    {
        return $this->onlyAvailableInAdminMenu();
    }

    public function orOnlyAvailableInRestApi(): OrComparison
    {
        return $this->onlyAvailableInRestApi();
    }

    public function orOnlyAvailableInTagCloud(): OrComparison
    {
        return $this->onlyAvailableInTagCloud();
    }

    public function orOnlyCustom(): OrComparison
    {
        return $this->onlyCustom();
    }

    public function orOnlyDefault(): OrComparison
    {
        return $this->onlyDefault();
    }

    public function orOnlyHiddenFromAdminMenu(): OrComparison
    {
        return $this->onlyHiddenFromAdminMenu();
    }

    public function orOnlyHiddenFromTagCloud(): OrComparison
    {
        return $this->onlyHiddenFromTagCloud();
    }

    public function orOnlyHiddenFromRestApi(): OrComparison
    {
        return $this->onlyHiddenFromRestApi();
    }

    public function orOnlyPrivate(): OrComparison
    {
        return $this->onlyPrivate();
    }

    public function orOnlyPublic(): OrComparison
    {
        return $this->onlyPublic();
    }

    public function orWhereAdminMenuLabel(string $label): OrComparison
    {
        return $this->whereAdminMenuLabel($label);
    }

    public function orWhereAdminMenuSingularLabel(string $singular_label): OrComparison
    {
        return $this->whereAdminMenuSingularLabel($singular_label);
    }

    public function orWhereAssignPermission(string $capability): OrComparison
    {
        return $this->whereAssignPermission($capability);
    }

    public function orWhereCanBeUsedBy(ObjectType $objectType): OrComparison
    {
        return $this->whereCanBeUsedBy($objectType);
    }

    public function orWhereCanOnlyBeUsedBy(ObjectType ...$objectTypes): OrComparison
    {
        return $this->whereCanOnlyBeUsedBy(...$objectTypes);
    }

    public function orWhereDeletePermission(string $capability): OrComparison
    {
        return $this->whereDeletePermission($capability);
    }

    public function orWhereEditPermission(string $capability): OrComparison
    {
        return $this->whereEditPermission($capability);
    }

    public function orWhereManagePermission(string $capability): OrComparison
    {
        return $this->whereManagePermission($capability);
    }

    public function orWhereName(string $name): OrComparison
    {
        return $this->whereName($name);
    }

    public function orWhereUrlQueryVariable(string $query_variable): OrComparison
    {
        return $this->whereUrlQueryVariable($query_variable);
    }
}
