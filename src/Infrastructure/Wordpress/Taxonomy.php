<?php

namespace Wordless\Infrastructure\Wordpress;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Taxonomy as Helper;
use Wordless\Application\Helpers\Taxonomy\Exceptions\TaxonomyNotRegistered;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions\InitializingModelWithWrongTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\Dictionary;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\MixinWpTerm;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Enums\MetableObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Traits\WithAcfs;
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

    final public static function objectType(): MetableObjectType
    {
        return MetableObjectType::term;
    }

    /**
     * @param WP_Term|int|string $term
     * @param bool $with_acfs
     * @throws TaxonomyNotRegistered
     * @throws InitializingModelWithWrongTaxonomyName
     * @throws InvalidArgumentException
     */
    public function __construct(WP_Term|int|string $term, bool $with_acfs = true)
    {
        $this->wpTerm = $term instanceof WP_Term ? $term : static::find($term);

        if (!$this->is($this->name())) {
            throw new InitializingModelWithWrongTaxonomyName($this, $with_acfs);
        }

        $this->wpTaxonomy = Helper::get($this->name());

        if ($with_acfs) {
            $this->loadTermAcfs($this->wpTerm->term_id);
        }
    }

    public function is(string $name): bool
    {
        return $this->taxonomy === $name;
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
     * @throws InvalidArgumentException
     */
    private function loadTermAcfs(int $from_id): void
    {
        $this->loadAcfs("{$this->name()}_$from_id");
    }
}
