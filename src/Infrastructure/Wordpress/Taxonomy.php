<?php

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Exceptions\InitializingModelWithWrongTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\MixinWpTerm;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Enums\MetableObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Traits\WithAcfs;
use WP_Taxonomy;
use WP_Term;

/**
 * @mixin WP_Term
 */
abstract class Taxonomy implements IRelatedMetaData
{
    use MixinWpTerm;
    use WithAcfs;
    use WithMetaData;

    abstract protected function setWpTaxonomy(): void;

    protected const NAME_KEY = null;

    protected WP_Taxonomy $wpTaxonomy;

    public static function objectType(): MetableObjectType
    {
        return MetableObjectType::term;
    }

    /**
     * @param WP_Term|int|string $term
     * @param bool $with_acfs
     * @throws InitializingModelWithWrongTaxonomyName
     */
    public function __construct(WP_Term|int|string $term, bool $with_acfs = true)
    {
        $this->wpTerm = $term instanceof WP_Term ? $term : static::find($term);

        if (!$this->is(static::NAME_KEY)) {
            throw new InitializingModelWithWrongTaxonomyName($this, $with_acfs);
        }

        $this->setWpTaxonomy();

        if ($with_acfs) {
            $this->loadTermAcfs($this->wpTerm->term_id);
        }
    }

    public function getWpTaxonomy(): WP_Taxonomy
    {
        return $this->wpTaxonomy;
    }

    public function is(string $name): bool
    {
        return $this->taxonomy === $name;
    }

    private function loadTermAcfs(int $from_id): void
    {
        $this->loadAcfs(static::NAME_KEY . "_$from_id");
    }
}
