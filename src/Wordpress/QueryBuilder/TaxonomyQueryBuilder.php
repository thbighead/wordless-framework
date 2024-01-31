<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Traits\ArgumentsBuilder;
use WP_Taxonomy;

final class TaxonomyQueryBuilder extends QueryBuilder
{
    use ArgumentsBuilder;

    private const ARGUMENT_KEY_BUILT_IN = '_builtin';

    private const ARGUMENT_KEY_OBJECT_TYPE = 'object_type';
    private const ARGUMENT_KEY_PUBLIC = 'public';
    private const ARGUMENT_KEY_SHOW_IN_REST = 'show_in_rest';
    private const ARGUMENT_KEY_SHOW_TAG_CLOUD = 'show_tagcloud';
    private const ARGUMENT_KEY_SHOW_UI = 'show_ui';

    public static function getInstance(ResultFormat $format = ResultFormat::objects, Operator $operator = Operator::and): TaxonomyQueryBuilder
    {
        return new self($format, $operator);
    }

    public function __construct(
        private readonly ResultFormat $format = ResultFormat::objects,
        private readonly Operator     $operator = Operator::and
    )
    {
    }

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
            $this->operator->name
        );
    }

    public function onlyAvailableInAdminMenu(): self
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_UI] = true;

        return $this;
    }

    public function onlyAvailableInRestApi(): self
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_IN_REST] = true;

        return $this;
    }

    public function onlyAvailableInTagCloud(): self
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_TAG_CLOUD] = true;

        return $this;
    }

    public function onlyCustom(): self
    {
        $this->arguments[self::ARGUMENT_KEY_BUILT_IN] = false;

        return $this;
    }

    public function onlyDefault(): self
    {
        $this->arguments[self::ARGUMENT_KEY_BUILT_IN] = true;

        return $this;
    }

    public function onlyHiddenFromAdminMenu(): self
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_UI] = false;

        return $this;
    }

    public function onlyHiddenFromTagCloud(): self
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_TAG_CLOUD] = false;

        return $this;
    }

    public function onlyHiddenFromRestApi(): self
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_IN_REST] = false;

        return $this;
    }

    public function onlyPrivate(): self
    {
        $this->arguments[self::ARGUMENT_KEY_PUBLIC] = false;

        return $this;
    }

    public function onlyPublic(): self
    {
        $this->arguments[self::ARGUMENT_KEY_PUBLIC] = true;

        return $this;
    }

    /**
     * @param string $label
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereAdminMenuLabel(string $label): self
    {
        $this->arguments['label'] = $this->validateStringParameter($label, __METHOD__);

        return $this;
    }

    /**
     * @param string $singular_label
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereAdminMenuSingularLabel(string $singular_label): self
    {
        $this->arguments['singular_label'] = $this->validateStringParameter($singular_label, __METHOD__);

        return $this;
    }

    /**
     * @param string $capability
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereAssignPermission(string $capability): self
    {
        $this->arguments['assign_cap'] = $this->validateStringParameter($capability, __METHOD__);

        return $this;
    }

    /**
     * @param ObjectType $objectType
     * @param ObjectType ...$objectTypes
     * @return $this
     */
    public function whereCanBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): self
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
    public function whereCanOnlyBeUsedBy(ObjectType $objectType, ObjectType ...$objectTypes): self
    {
        $this->arguments[self::ARGUMENT_KEY_OBJECT_TYPE] = Arr::prepend($objectTypes, $objectType);

        return $this;
    }

    /**
     * @param string $capability
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereDeletePermission(string $capability): self
    {
        $this->arguments['delete_cap'] = $this->validateStringParameter($capability, __METHOD__);

        return $this;
    }

    /**
     * @param string $capability
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereEditPermission(string $capability): self
    {
        $this->arguments['edit_cap'] = $this->validateStringParameter($capability, __METHOD__);

        return $this;
    }

    /**
     * @param string $capability
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereManagePermission(string $capability): self
    {
        $this->arguments['manage_cap'] = $this->validateStringParameter($capability, __METHOD__);

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereName(string $name): self
    {
        $this->arguments['name'] = $this->validateStringParameter($name, __METHOD__);

        return $this;
    }

    /**
     * @param string $query_variable
     * @return $this
     * @throws EmptyStringParameter
     */
    public function whereUrlQueryVariable(string $query_variable): self
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
