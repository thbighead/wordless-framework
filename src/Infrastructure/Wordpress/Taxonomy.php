<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions\InitializingModelWithWrongTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\Dictionary;
use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\FailedAggregatingObject;
use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\FailedDisaggregatingObject;
use Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions\FailedToGetTermLink;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\MixinWpTerm;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Traits\WithAcfs;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use WP_Error;
use WP_Taxonomy;
use WP_Term;

/**
 * @mixin WP_Term
 */
abstract class Taxonomy implements IRelatedMetaData
{
    use MixinWpTerm;
    use Repository;
    use WithAcfs;
    use WithMetaData;

    abstract protected static function getDictionary(): Dictionary;

    protected const NAME_KEY = null;

    public readonly WP_Taxonomy $wpTaxonomy;
    protected string $url;
    /** @var static|null $parent */
    private ?Taxonomy $parent;

    final public static function getNameKey(): string
    {
        return static::NAME_KEY;
    }

    final public static function objectType(): ObjectType
    {
        return ObjectType::term;
    }

    /**
     * @param WP_Term|int|string $term
     * @param bool $with_acfs
     * @throws EmptyQueryBuilderArguments
     * @throws EmptyStringParameter
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws InvalidAcfFunction
     * @throws InvalidArgumentException
     */
    public function __construct(WP_Term|int|string $term, bool $with_acfs = true)
    {
        $this->wpTerm = ($term instanceof WP_Term ? $term : static::get($term)) ?? static::find($term);

        if (!$this->is($this->name())) {
            throw new InitializingModelWithWrongTaxonomyName($this, $with_acfs);
        }

        $this->wpTaxonomy = TaxonomyQueryBuilder::make()->whereName($this->name())->first();

        if ($with_acfs) {
            $this->loadTermAcfs($this->wpTerm->term_id);
        }
    }

    /**
     * @param IRelatedMetaData|int $object
     * @return $this
     * @throws FailedAggregatingObject
     */
    public function appendToObject(IRelatedMetaData|int $object): static
    {
        $result = wp_set_object_terms(
            is_int($object) ? $object : $object->id(),
            $this->id(),
            $this->taxonomy,
            true
        );

        if ($result instanceof WP_Error || $result === false) {
            throw new FailedAggregatingObject($object, $this, $result);
        }

        return $this;
    }

    /**
     * @return bool
     * @throws EmptyQueryBuilderArguments
     * @throws EmptyStringParameter
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws InvalidAcfFunction
     * @throws InvalidArgumentException
     */
    public function hasParent(): bool
    {
        return !is_null($this->parent());
    }

    public function is(string $name): bool
    {
        return $this->taxonomy === $name;
    }

    /**
     * @param bool $with_acfs
     * @return $this|null
     * @throws EmptyQueryBuilderArguments
     * @throws EmptyStringParameter
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws InvalidAcfFunction
     * @throws InvalidArgumentException
     */
    public function parent(bool $with_acfs = false): ?static
    {
        return $this->parent
            ?? $this->parent = $this->wpTerm->parent > 0 ? new static($this->wpTerm->parent, $with_acfs) : null;
    }

    /**
     * @param IRelatedMetaData|int $object
     * @return $this
     * @throws FailedDisaggregatingObject
     */
    public function removeFromObject(IRelatedMetaData|int $object): static
    {
        $result = wp_remove_object_terms(
            is_int($object) ? $object : $object->id(),
            $this->id(),
            $this->taxonomy
        );

        if ($result instanceof WP_Error || $result === false) {
            throw new FailedDisaggregatingObject($object, $this, $result);
        }

        return $this;
    }

    /**
     * @return string
     * @throws FailedToGetTermLink
     */
    public function url(): string
    {
        if (isset($this->url)) {
            return $this->url;
        }

        if (!is_string($url = get_term_link($wpTerm = $this->asWpTerm(), static::NAME_KEY))) {
            throw new FailedToGetTermLink($wpTerm, $url);
        }

        return $this->url = $url;
    }

    final public function id(): int
    {
        return $this->term_id;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    protected function name(): string
    {
        return static::NAME_KEY ?? Str::slugCase(static::class);
    }

    /**
     * @param int $from_id
     * @return void
     * @throws InvalidAcfFunction
     * @throws InvalidArgumentException
     */
    private function loadTermAcfs(int $from_id): void
    {
        $this->loadAcfs("{$this->name()}_$from_id");
    }
}
