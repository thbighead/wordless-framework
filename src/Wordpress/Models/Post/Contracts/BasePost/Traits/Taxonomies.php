<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Contracts\BasePost\Traits;

use Wordless\Application\Helpers\Expect;
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

    private function taxonomiesTermsToArray(): array
    {
        $array = [];

        foreach ($this->taxonomiesTerms as $taxonomy => $terms) {
            if (empty($terms)) {
                continue;
            }

            foreach ($terms as $term) {
                $array[$taxonomy][] = $term->to_array();
            }
        }

        return $array;
    }
}
