<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\AndComparison;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\AndWhereComparisons;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\NotWhereComparisons;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\OrWhereComparisons;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\NotComparison;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\OrComparison;

final class TaxonomyQueryBuilder extends BaseTaxonomyQueryBuilder implements AndWhereComparisons,
    NotWhereComparisons,
    OrWhereComparisons
{
    public static function getInstance(ResultFormat $format = ResultFormat::objects): TaxonomyQueryBuilder
    {
        return new self($format);
    }

    public function __construct(ResultFormat $format = ResultFormat::objects)
    {
        $this->format = $format;
    }

    public function andOnlyAvailableInAdminMenu(): AndComparison
    {
        return new AndComparison($this->onlyAvailableInAdminMenu());
    }

    public function andOnlyAvailableInRestApi(): AndComparison
    {
        return new AndComparison($this->onlyAvailableInRestApi());
    }

    public function andOnlyAvailableInTagCloud(): AndComparison
    {
        return new AndComparison($this->onlyAvailableInTagCloud());
    }

    public function andOnlyCustom(): AndComparison
    {
        return new AndComparison($this->onlyCustom());
    }

    public function andOnlyDefault(): AndComparison
    {
        return new AndComparison($this->onlyDefault());
    }

    public function andOnlyHiddenFromAdminMenu(): AndComparison
    {
        return new AndComparison($this->onlyHiddenFromAdminMenu());
    }

    public function andOnlyHiddenFromTagCloud(): AndComparison
    {
        return new AndComparison($this->onlyHiddenFromTagCloud());
    }

    public function andOnlyHiddenFromRestApi(): AndComparison
    {
        return new AndComparison($this->onlyHiddenFromRestApi());
    }

    public function andOnlyPrivate(): AndComparison
    {
        return new AndComparison($this->onlyPrivate());
    }

    public function andOnlyPublic(): AndComparison
    {
        return new AndComparison($this->onlyPublic());
    }

    public function andWhereAdminMenuLabel(string $label): AndComparison
    {
        return new AndComparison($this->whereAdminMenuLabel($label));
    }

    public function andWhereAdminMenuSingularLabel(string $singular_label): AndComparison
    {
        return new AndComparison($this->whereAdminMenuSingularLabel($singular_label));
    }

    public function andWhereAssignPermission(string $capability): AndComparison
    {
        return new AndComparison($this->whereAssignPermission($capability));
    }

    public function andWhereCanBeUsedBy(ObjectType $objectType): AndComparison
    {
        return new AndComparison($this->whereCanBeUsedBy($objectType));
    }

    public function andWhereCanOnlyBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): AndComparison
    {
        return new AndComparison($this->whereCanOnlyBeUsedBy(...Arr::prepend($objectTypes, $objectType)));
    }

    public function andWhereDeletePermission(string $capability): AndComparison
    {
        return new AndComparison($this->whereDeletePermission($capability));
    }

    public function andWhereEditPermission(string $capability): AndComparison
    {
        return new AndComparison($this->whereEditPermission($capability));
    }

    public function andWhereManagePermission(string $capability): AndComparison
    {
        return new AndComparison($this->whereManagePermission($capability));
    }

    public function andWhereName(string $name): AndComparison
    {
        return new AndComparison($this->whereName($name));
    }

    public function andWhereUrlQueryVariable(string $query_variable): AndComparison
    {
        return new AndComparison($this->whereUrlQueryVariable($query_variable));
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getResultFormat(): ResultFormat
    {
        return $this->format;
    }

    public function notOnlyAvailableInAdminMenu(): NotComparison
    {
        return new NotComparison($this->onlyAvailableInAdminMenu());
    }

    public function notOnlyAvailableInRestApi(): NotComparison
    {
        return new NotComparison($this->onlyAvailableInRestApi());
    }

    public function notOnlyAvailableInTagCloud(): NotComparison
    {
        return new NotComparison($this->onlyAvailableInTagCloud());
    }

    public function notOnlyCustom(): NotComparison
    {
        return new NotComparison($this->onlyCustom());
    }

    public function notOnlyDefault(): NotComparison
    {
        return new NotComparison($this->onlyDefault());
    }

    public function notOnlyHiddenFromAdminMenu(): NotComparison
    {
        return new NotComparison($this->onlyHiddenFromAdminMenu());
    }

    public function notOnlyHiddenFromTagCloud(): NotComparison
    {
        return new NotComparison($this->onlyHiddenFromTagCloud());
    }

    public function notOnlyHiddenFromRestApi(): NotComparison
    {
        return new NotComparison($this->onlyHiddenFromRestApi());
    }

    public function notOnlyPrivate(): NotComparison
    {
        return new NotComparison($this->onlyPrivate());
    }

    public function notOnlyPublic(): NotComparison
    {
        return new NotComparison($this->onlyPublic());
    }

    public function notWhereAdminMenuLabel(string $label): NotComparison
    {
        return new NotComparison($this->whereAdminMenuLabel($label));
    }

    public function notWhereAdminMenuSingularLabel(string $singular_label): NotComparison
    {
        return new NotComparison($this->whereAdminMenuSingularLabel($singular_label));
    }

    public function notWhereAssignPermission(string $capability): NotComparison
    {
        return new NotComparison($this->whereAssignPermission($capability));
    }

    public function notWhereCanBeUsedBy(ObjectType $objectType): NotComparison
    {
        return new NotComparison($this->whereCanBeUsedBy($objectType));
    }

    public function notWhereCanOnlyBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): NotComparison
    {
        return new NotComparison($this->whereCanOnlyBeUsedBy(...Arr::prepend($objectTypes, $objectType)));
    }

    public function notWhereDeletePermission(string $capability): NotComparison
    {
        return new NotComparison($this->whereDeletePermission($capability));
    }

    public function notWhereEditPermission(string $capability): NotComparison
    {
        return new NotComparison($this->whereEditPermission($capability));
    }

    public function notWhereManagePermission(string $capability): NotComparison
    {
        return new NotComparison($this->whereManagePermission($capability));
    }

    public function notWhereName(string $name): NotComparison
    {
        return new NotComparison($this->whereName($name));
    }

    public function notWhereUrlQueryVariable(string $query_variable): NotComparison
    {
        return new NotComparison($this->whereUrlQueryVariable($query_variable));
    }

    public function orOnlyAvailableInAdminMenu(): OrComparison
    {
        return new OrComparison($this->onlyAvailableInAdminMenu());
    }

    public function orOnlyAvailableInRestApi(): OrComparison
    {
        return new OrComparison($this->onlyAvailableInRestApi());
    }

    public function orOnlyAvailableInTagCloud(): OrComparison
    {
        return new OrComparison($this->onlyAvailableInAdminMenu());
    }

    public function orOnlyCustom(): OrComparison
    {
        return new OrComparison($this->onlyCustom());
    }

    public function orOnlyDefault(): OrComparison
    {
        return new OrComparison($this->onlyDefault());
    }

    public function orOnlyHiddenFromAdminMenu(): OrComparison
    {
        return new OrComparison($this->onlyHiddenFromAdminMenu());
    }

    public function orOnlyHiddenFromTagCloud(): OrComparison
    {
        return new OrComparison($this->onlyHiddenFromTagCloud());
    }

    public function orOnlyHiddenFromRestApi(): OrComparison
    {
        return new OrComparison($this->onlyHiddenFromRestApi());
    }

    public function orOnlyPrivate(): OrComparison
    {
        return new OrComparison($this->onlyPrivate());
    }

    public function orOnlyPublic(): OrComparison
    {
        return new OrComparison($this->onlyPublic());
    }

    public function orWhereAdminMenuLabel(string $label): OrComparison
    {
        return new OrComparison($this->whereAdminMenuLabel($label));
    }

    public function orWhereAdminMenuSingularLabel(string $singular_label): OrComparison
    {
        return new OrComparison($this->whereAdminMenuSingularLabel($singular_label));
    }

    public function orWhereAssignPermission(string $capability): OrComparison
    {
        return new OrComparison($this->whereAssignPermission($capability));
    }

    public function orWhereCanBeUsedBy(ObjectType $objectType): OrComparison
    {
        return new OrComparison($this->whereCanBeUsedBy($objectType));
    }

    public function orWhereCanOnlyBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): OrComparison
    {
        return new OrComparison($this->whereCanOnlyBeUsedBy(...Arr::prepend($objectTypes, $objectType)));
    }

    public function orWhereDeletePermission(string $capability): OrComparison
    {
        return new OrComparison($this->whereDeletePermission($capability));
    }

    public function orWhereEditPermission(string $capability): OrComparison
    {
        return new OrComparison($this->whereEditPermission($capability));
    }

    public function orWhereManagePermission(string $capability): OrComparison
    {
        return new OrComparison($this->whereManagePermission($capability));
    }

    public function orWhereName(string $name): OrComparison
    {
        return new OrComparison($this->whereName($name));
    }

    public function orWhereUrlQueryVariable(string $query_variable): OrComparison
    {
        return new OrComparison($this->whereUrlQueryVariable($query_variable));
    }
}
