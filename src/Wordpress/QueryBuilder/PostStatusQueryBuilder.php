<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use stdClass;
use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder;
use Wordless\Wordpress\QueryBuilder\Enums\ResultFormat;
use Wordless\Wordpress\QueryBuilder\PostStatusQueryBuilder\Enums\Operator;

class PostStatusQueryBuilder extends QueryBuilder
{
    private const ARGUMENT_KEY_BUILT_IN = '_builtin';
    private const ARGUMENT_KEY_DATE_FLOATING = 'date_floating';
    private const ARGUMENT_KEY_EXCLUDE_FROM_SEARCH = 'exclude_from_search';
    private const ARGUMENT_KEY_INTERNAL = 'internal';
    private const ARGUMENT_KEY_PUBLIC = 'public';
    private const ARGUMENT_KEY_PUBLICLY_QUERYABLE = 'publicly_queryable';
    private const ARGUMENT_KEY_PRIVATE = 'private';
    private const ARGUMENT_KEY_PROTECTED = 'protected';
    private const ARGUMENT_KEY_SHOW_IN_ADMIN_ALL_LIST = 'show_in_admin_all_list';
    private const ARGUMENT_KEY_SHOW_IN_ADMIN_STATUS_LIST = 'show_in_admin_status_list';

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
     * @param int $quantity
     * @param ResultFormat|null $format
     * @return string|stdClass|string[]|array<string, stdClass>|null
     */
    public function first(int $quantity = 1, ?ResultFormat $format = null): string|stdClass|array|null
    {
        return Arr::first($this->get($format), $quantity);
    }

    /**
     * @param ResultFormat|null $format
     * @return string[]|array<string, stdClass>
     */
    public function get(?ResultFormat $format = null): array
    {
        return get_post_stati(
            $this->buildArguments(),
            ($format ?? $this->format)->name,
            $this->operator->name
        );
    }

    public function onlyCustom(): static
    {
        $this->arguments[self::ARGUMENT_KEY_BUILT_IN] = false;

        return $this;
    }

    public function onlyDateFloating(): static
    {
        $this->arguments[self::ARGUMENT_KEY_DATE_FLOATING] = true;

        return $this;
    }

    public function onlyDateNotFloating(): static
    {
        $this->arguments[self::ARGUMENT_KEY_DATE_FLOATING] = false;

        return $this;
    }

    public function onlyDefault(): static
    {
        $this->arguments[self::ARGUMENT_KEY_BUILT_IN] = true;

        return $this;
    }

    public function onlyExcludedFromSearch(): static
    {
        $this->arguments[self::ARGUMENT_KEY_EXCLUDE_FROM_SEARCH] = true;

        return $this;
    }

    public function onlyExternal(): static
    {
        $this->arguments[self::ARGUMENT_KEY_INTERNAL] = false;

        return $this;
    }

    public function onlyHiddenInAdminAllList(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_IN_ADMIN_ALL_LIST] = false;

        return $this;
    }

    public function onlyHiddenInAdminStatusList(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_IN_ADMIN_STATUS_LIST] = false;

        return $this;
    }

    public function onlyIncludedFromSearch(): static
    {
        $this->arguments[self::ARGUMENT_KEY_EXCLUDE_FROM_SEARCH] = false;

        return $this;
    }

    public function onlyInternal(): static
    {
        $this->arguments[self::ARGUMENT_KEY_INTERNAL] = true;

        return $this;
    }

    public function onlyPrivate(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PRIVATE] = true;

        return $this;
    }

    public function onlyProtected(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PROTECTED] = true;

        return $this;
    }

    public function onlyPublic(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PUBLIC] = true;

        return $this;
    }

    public function onlyPubliclyQueryable(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PUBLICLY_QUERYABLE] = true;

        return $this;
    }

    public function onlyNotPrivate(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PRIVATE] = false;

        return $this;
    }

    public function onlyNotProtected(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PROTECTED] = false;

        return $this;
    }

    public function onlyNotPublic(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PUBLIC] = false;

        return $this;
    }

    public function onlyNotPubliclyQueryable(): static
    {
        $this->arguments[self::ARGUMENT_KEY_PUBLICLY_QUERYABLE] = false;

        return $this;
    }

    public function onlyShownInAdminAllList(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_IN_ADMIN_ALL_LIST] = true;

        return $this;
    }

    public function onlyShownInAdminStatusList(): static
    {
        $this->arguments[self::ARGUMENT_KEY_SHOW_IN_ADMIN_STATUS_LIST] = true;

        return $this;
    }

    public function whereLabel(string $label): static
    {
        $this->arguments['label'] = $label;

        return $this;
    }

    public function whereName(string $name): static
    {
        $this->arguments['name'] = $name;

        return $this;
    }

    public function whereSlug(string $slug): static
    {
        return $this->whereName($slug);
    }

    /**
     * @return array|array[]|bool[]|int[]|string[]
     */
    protected function buildArguments(): array
    {
        return $this->arguments;
    }
}
