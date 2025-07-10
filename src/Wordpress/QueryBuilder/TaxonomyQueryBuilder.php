<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Traits\ArgumentsBuilder;
use WP_Taxonomy;

class TaxonomyQueryBuilder extends QueryBuilder
{
    use ArgumentsBuilder;

    private const ARGUMENT_KEY_BUILT_IN = '_builtin';
    private const ARGUMENT_KEY_OBJECT_TYPE = 'object_type';
    private const ARGUMENT_KEY_PUBLIC = 'public';
    private const ARGUMENT_KEY_SHOW_IN_REST = 'show_in_rest';
    private const ARGUMENT_KEY_SHOW_TAG_CLOUD = 'show_tagcloud';
    private const ARGUMENT_KEY_SHOW_UI = 'show_ui';

    public static function make(
        ResultFormat $format = ResultFormat::objects,
        Operator     $operator = Operator::and
    ): static
    {
        return new static($format, $operator);
    }

    public function __construct(
        private readonly ResultFormat $format = ResultFormat::objects,
        private readonly Operator     $operator = Operator::and
    )
    {
    }

    /**
     * @return bool
     * @throws EmptyQueryBuilderArguments
     */
    public function exists(): bool
    {
        return !empty($this->get());
    }

    /**
     * @param int $quantity
     * @param ResultFormat|null $format
     * @return WP_Taxonomy|array|string|null
     * @throws EmptyQueryBuilderArguments
     */
    public function first(int $quantity = 1, ?ResultFormat $format = null): WP_Taxonomy|array|string|null
    {
        return Arr::first($this->get($format), $quantity) ?? null;
    }

    /**
     * @param ResultFormat|null $format
     * @return string[]|WP_Taxonomy[]
     * @throws EmptyQueryBuilderArguments
     */
    public function get(?ResultFormat $format = null): array
    {
        return get_taxonomies(
            $this->buildArguments(),
            $format ?? $this->format->name,
            $this->operator->name
        );
    }

    public function onlyAvailableInAdminMenu(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_UI] = true;

        return $this;
    }

    public function onlyAvailableInRestApi(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_IN_REST] = true;

        return $this;
    }

    public function onlyAvailableInTagCloud(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_TAG_CLOUD] = true;

        return $this;
    }

    public function onlyCustom(): static
    {
        $this->arguments[self::ARGUMENT_KEY_BUILT_IN] = false;

        return $this;
    }

    public function onlyDefault(): static
    {
        $this->arguments[self::ARGUMENT_KEY_BUILT_IN] = true;

        return $this;
    }

    public function onlyHiddenFromAdminMenu(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_UI] = false;

        return $this;
    }

    public function onlyHiddenFromTagCloud(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_TAG_CLOUD] = false;

        return $this;
    }

    public function onlyHiddenFromRestApi(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_IN_REST] = false;

        return $this;
    }

    public function onlyPrivate(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PUBLIC] = false;

        return $this;
    }

    public function onlyPublic(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PUBLIC] = true;

        return $this;
    }

    /**
     * @param string $label
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereAdminMenuLabel(string $label): static
    {
        $this->arguments['label'] = $this->validateStringParameter($label, __METHOD__);

        return $this;
    }

    /**
     * @param string $singular_label
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereAdminMenuSingularLabel(string $singular_label): static
    {
        $this->arguments['singular_label'] = $this->validateStringParameter($singular_label, __METHOD__);

        return $this;
    }

    /**
     * @param string $capability
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereAssignPermission(string $capability): static
    {
        $this->arguments['assign_cap'] = $this->validateStringParameter($capability, __METHOD__);

        return $this;
    }

    /**
     * @param ObjectType $objectType
     * @param ObjectType ...$objectTypes
     * @return $this
     */
    public function whereCanBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): static
    {
        if (!isset($this->arguments[self::ARGUMENT_KEY_OBJECT_TYPE])) {
            $this->arguments[self::ARGUMENT_KEY_OBJECT_TYPE] = [];
        }

        $this->arguments[self::ARGUMENT_KEY_OBJECT_TYPE] = array_merge(
            $this->arguments[self::ARGUMENT_KEY_OBJECT_TYPE],
            Arr::prepend($objectTypes, $objectType)
        );

        return $this;
    }

    /**
     * @param ObjectType $objectType
     * @param ObjectType ...$objectTypes
     * @return $this
     */
    public function whereCanOnlyBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): static
    {
        $this->arguments[self::ARGUMENT_KEY_OBJECT_TYPE] = Arr::prepend($objectTypes, $objectType);

        return $this;
    }

    /**
     * @param string $capability
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereDeletePermission(string $capability): static
    {
        $this->arguments['delete_cap'] = $this->validateStringParameter($capability, __METHOD__);

        return $this;
    }

    /**
     * @param string $capability
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereEditPermission(string $capability): static
    {
        $this->arguments['edit_cap'] = $this->validateStringParameter($capability, __METHOD__);

        return $this;
    }

    /**
     * @param string $capability
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereManagePermission(string $capability): static
    {
        $this->arguments['manage_cap'] = $this->validateStringParameter($capability, __METHOD__);

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereName(string $name): static
    {
        $this->arguments['name'] = $this->validateStringParameter($name, __METHOD__);

        return $this;
    }

    /**
     * @param string $query_variable
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereUrlQueryVariable(string $query_variable): static
    {
        $this->arguments['query_var'] = $this->validateStringParameter($query_variable, __METHOD__);

        return $this;
    }

    /**
     * @param string $parameter_value
     * @param string $method
     * @return string
     * @throws EmptyStringParameter
     */
    private function validateStringParameter(string $parameter_value, string $method): string
    {
        if ($parameter_value === '') {
            throw new EmptyStringParameter($method);
        }

        return $parameter_value;
    }
}
