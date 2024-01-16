<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Contracts\BaseTaxonomyQueryBuilder\Traits\ArgumentsBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\WhereOperator;
use WP_Taxonomy;

abstract class BaseTaxonomyQueryBuilder extends QueryBuilder
{
    use ArgumentsBuilder;

    private const ARGUMENT_KEY_BUILT_IN = '_builtin';
    private const ARGUMENT_KEY_OBJECT_TYPE = 'object_type';
    private const ARGUMENT_KEY_PUBLIC = 'public';
    private const ARGUMENT_KEY_SHOW_IN_REST = 'show_in_rest';
    private const ARGUMENT_KEY_SHOW_TAG_CLOUD = 'show_tagcloud';
    private const ARGUMENT_KEY_SHOW_UI = 'show_ui';

    protected ResultFormat $format;
    protected WhereOperator $operator;

    public function first(int $quantity = 1, ?ResultFormat $format = null): WP_Taxonomy|array|string|null
    {
        $full_result = $this->get($format);

        if ($quantity > 1) {
            return array_slice($full_result, 0, $quantity);
        }

        return $full_result[0] ?? null;
    }

    /**
     * @param ResultFormat|null $format
     * @return string[]|WP_Taxonomy[]
     */
    public function get(?ResultFormat $format = null): array
    {
        return get_taxonomies(
            $this->buildArguments(),
            $format ?? $this->format->name,
            ($this->operator ?? WhereOperator::and)->name
        );
    }

    final protected function onlyAvailableInAdminMenu(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_UI] = true;

        return $this;
    }

    final protected function onlyAvailableInRestApi(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_IN_REST] = true;

        return $this;
    }

    final protected function onlyAvailableInTagCloud(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_TAG_CLOUD] = true;

        return $this;
    }

    final protected function onlyCustom(): static
    {
        $this->arguments[self::ARGUMENT_KEY_BUILT_IN] = false;

        return $this;
    }

    final protected function onlyDefault(): static
    {
        $this->arguments[self::ARGUMENT_KEY_BUILT_IN] = true;

        return $this;
    }

    final protected function onlyHiddenFromAdminMenu(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_UI] = false;

        return $this;
    }

    final protected function onlyHiddenFromTagCloud(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_TAG_CLOUD] = false;

        return $this;
    }

    final protected function onlyHiddenFromRestApi(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_IN_REST] = false;

        return $this;
    }

    final protected function onlyPrivate(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PUBLIC] = false;

        return $this;
    }

    final protected function onlyPublic(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PUBLIC] = true;

        return $this;
    }

    final protected function whereAdminMenuLabel(string $label): static
    {
        $this->arguments['label'] = $label;

        return $this;
    }

    final protected function whereAdminMenuSingularLabel(string $singular_label): static
    {
        $this->arguments['singular_label'] = $singular_label;

        return $this;
    }

    final protected function whereAssignPermission(string $capability): static
    {
        $this->arguments['assign_cap'] = $capability;

        return $this;
    }

    /**
     * @param ObjectType $objectType
     * @return $this
     */
    final protected function whereCanBeUsedBy(ObjectType $objectType): static
    {
        isset($this->arguments[self::ARGUMENT_KEY_OBJECT_TYPE]) ?
            $this->arguments[self::ARGUMENT_KEY_OBJECT_TYPE][] = $objectType :
            $this->arguments[self::ARGUMENT_KEY_OBJECT_TYPE] = [$objectType];

        return $this;
    }

    /**
     * @param ObjectType $objectType
     * @param ObjectType ...$objectTypes
     * @return $this
     */
    final protected function whereCanOnlyBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): static
    {
        $this->arguments[self::ARGUMENT_KEY_OBJECT_TYPE] = Arr::prepend($objectTypes, $objectType);

        return $this;
    }

    final protected function whereDeletePermission(string $capability): static
    {
        $this->arguments['delete_cap'] = $capability;

        return $this;
    }

    final protected function whereEditPermission(string $capability): static
    {
        $this->arguments['edit_cap'] = $capability;

        return $this;
    }

    final protected function whereManagePermission(string $capability): static
    {
        $this->arguments['manage_cap'] = $capability;

        return $this;
    }

    final protected function whereName(string $name): static
    {
        $this->arguments['name'] = $name;

        return $this;
    }

    final protected function whereUrlQueryVariable(string $query_variable): static
    {
        $this->arguments['query_var'] = $query_variable;

        return $this;
    }
}
