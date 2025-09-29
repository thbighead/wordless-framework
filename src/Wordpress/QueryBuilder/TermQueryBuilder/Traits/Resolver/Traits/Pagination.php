<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver\Traits;

use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Enums\TermsListFormat;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedTerms;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedTerms\Rotating;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver\Traits\Pagination\PaginatedTerms\Exceptions\FailedToConstructPaginatedTerms;

trait Pagination
{
    public function offset(int $offset): static
    {
        if (($offset = max($offset, 0)) > 0) {
            $this->arguments['offset'] = $offset;
        }

        return $this;
    }

    /**
     * @param int $terms_per_page
     * @param TermsListFormat $format
     * @param array $extra_arguments
     * @return PaginatedTerms
     * @throws FailedToConstructPaginatedTerms
     */
    public function paginate(
        int             $terms_per_page,
        TermsListFormat $format = TermsListFormat::wp_terms,
        array           $extra_arguments = []
    ): PaginatedTerms
    {
        $this->arguments[TermsListFormat::FIELDS_KEY] = $format->value;

        return new PaginatedTerms(
            $this->resolveExtraArguments($this->arguments, $extra_arguments),
            max($terms_per_page, 1)
        );
    }

    /**
     * @param int $terms_per_page
     * @param TermsListFormat $format
     * @param array $extra_arguments
     * @return Rotating
     * @throws FailedToConstructPaginatedTerms
     */
    public function paginateRotating(
        int             $terms_per_page,
        TermsListFormat $format = TermsListFormat::wp_terms,
        array           $extra_arguments = []
    ): Rotating
    {
        $this->arguments[TermsListFormat::FIELDS_KEY] = $format->value;

        return new Rotating(
            $this->resolveExtraArguments($this->arguments, $extra_arguments),
            max($terms_per_page, 1)
        );
    }
}
