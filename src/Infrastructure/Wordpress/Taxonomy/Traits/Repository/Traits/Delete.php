<?php

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits;

use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits\Delete\Exceptions\DeleteTermError;
use WP_Error;
use WP_Term;

trait Delete
{
    /**
     * @param Taxonomy|WP_Term|int $term
     * @return void
     * @throws DeleteTermError
     */
    public static function delete(Taxonomy|WP_Term|int $term): void
    {
        if (!is_int($term)) {
            $term = $term->term_id;
        }

        if ($term <= 0) {
            return;
        }

        if (($result = wp_delete_term($term, static::getNameKey())) instanceof WP_Error) {
            throw new DeleteTermError($result);
        }
    }

    /**
     * @return void
     * @throws DeleteTermError
     */
    public static function truncate(): void
    {
        foreach (static::all() as $term) {
            /** @var WP_Term $term */
            static::delete($term->term_id);
        }
    }
}
