<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\WpQueryBuilder;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Author;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Id;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\Traits\Resolver;
use Wordless\Wordpress\QueryBuilder\Traits\HasDateSubQuery;
use Wordless\Wordpress\QueryBuilder\Traits\HasMetaSubQuery;
use WP_Comment_Query;

class CommentQueryBuilder extends WpQueryBuilder
{
    use Author;
    use HasDateSubQuery;
    use HasMetaSubQuery;
    use Id;
    use Resolver;

    private const KEY_INCLUDE_UNAPPROVED = 'include_unapproved';

    public function limit(int $how_many): static
    {
        $this->arguments['number'] = max(1, $how_many);

        return $this;
    }

    public function whereKarma(int $karma_score): static
    {
        $this->arguments['karma'] = $karma_score;

        return $this;
    }

    public function withChildren(): static
    {
        $this->arguments['hierarchical'] = 'threaded';

        return $this;
    }

    /**
     * @return WP_Comment_Query
     */
    protected function getQuery(): WP_Comment_Query
    {
        return parent::getQuery();
    }

    protected function mountNewWpQuery(): WP_Comment_Query
    {
        return new WP_Comment_Query;
    }
}
