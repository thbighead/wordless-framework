<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Comment
{
    public function withAnyComments(): static
    {
        return $this->withComments();
    }

    /**
     * @param int $how_many
     * @param Operator $comparison
     * @return PostQueryBuilder
     */
    public function withComments(
        int      $how_many = 1,
        Operator $comparison = Operator::greater_than_or_equal
    ): static
    {
        $this->arguments['comment_count'] = ['compare' => $comparison, 'value' => $how_many];

        return $this;
    }

    /**
     * @param int $how_many
     * @return PostQueryBuilder
     */
    public function withDifferentThanComments(int $how_many): static
    {
        return $this->withComments($how_many, Operator::different);
    }

    /**
     * @param int $how_many
     * @return PostQueryBuilder
     */
    public function withLessThanComments(int $how_many): static
    {
        return $this->withComments($how_many, Operator::less_than);
    }

    /**
     * @param int $how_many
     * @return PostQueryBuilder
     */
    public function withLessThanOrEqualsComments(int $how_many): static
    {
        return $this->withComments($how_many, Operator::less_than_or_equal);
    }

    /**
     * @param int $how_many
     * @return PostQueryBuilder
     */
    public function withMoreThanComments(int $how_many): static
    {
        return $this->withComments($how_many, Operator::greater_than);
    }

    /**
     * @param int $how_many
     * @return PostQueryBuilder
     */
    public function withMoreThanOrEqualsComments(int $how_many): static
    {
        return $this->withComments($how_many);
    }

    public function withoutComments(): static
    {
        return $this->withComments(0, Operator::equal);
    }
}
