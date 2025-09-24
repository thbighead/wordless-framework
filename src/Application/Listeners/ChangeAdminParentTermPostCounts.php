<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Hook\Enums\Action;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Exceptions\EmptyStringParameter;
use WP_Term_Query;

class ChangeAdminParentTermPostCounts extends ActionListener
{
    protected const FUNCTION = 'countWithChildren';

    /**
     * @param WP_Term_Query $query
     * @return void
     * @throws EmptyQueryBuilderArguments
     */
    public static function countWithChildren(WP_Term_Query $query): void
    {
        global $pagenow;

        if (is_admin() && $pagenow === 'edit-tags.php' && self::isRequestedTaxonomyHierarchical()) {
            $query->query_vars['pad_counts'] = true;
        }
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): ActionHook
    {
        return Action::pre_get_terms;
    }

    private static function isRequestedTaxonomyHierarchical(): bool
    {
        try {
            return TaxonomyQueryBuilder::make()->whereName($_REQUEST['taxonomy'] ?? '')
                ->first()
                ?->hierarchical ?? false;
        } catch (EmptyStringParameter) {
            return false;
        }
    }
}
