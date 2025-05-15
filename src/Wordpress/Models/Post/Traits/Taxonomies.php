<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Expect;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Wordpress\Models\Category;
use WP_Term;

trait Taxonomies
{
    /** @var array<string, WP_Term[]> $taxonomiesTerms */
    private array $taxonomiesTerms = [];

    public function getTerms(string $taxonomy): array
    {
        if (isset($this->taxonomiesTerms[$taxonomy])) {
            return $this->taxonomiesTerms[$taxonomy];
        }

        return $this->taxonomiesTerms[$taxonomy] = Expect::array(get_the_terms($this->ID, $taxonomy));
    }
}
