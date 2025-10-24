<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\TermModelQueryBuilder\MultipleUpdateBuilder\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\Taxonomy;

class CannotUpdateMultipleTermsWithSameSlug extends InvalidArgumentException
{
    /**
     * @param Taxonomy[] $terms
     * @param string $slug
     * @param Throwable|null $previous
     */
    public function __construct(
        public readonly array  $terms,
        public readonly string $slug,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            'Slugs are unique. You cannot update all '
            . count($this->terms)
            . " terms slugs to $this->slug",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
