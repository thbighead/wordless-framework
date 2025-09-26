<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\Enums\Direction;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\OrderBy\Enums\OrderByColumn;

trait OrderBy
{
    /**
     * The $column string type possibility is to accomplish the $meta_query documented possibility, but please pay
     * attention in the following user comment in WordPress docs:
     *
     * """""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""
     * The specification for providing meta_query keys as orderby parameters is confusing at best. The docs say to use
     * "fields as keys" and an accepted value is an "array key of $meta_query". In fact values must be the array key
     * from the array returned by $WP_Meta_Query::get_clauses(). You could get this array by instantiating
     * WP_Meta_Query with your meta_query arguments, then calling WP_Meta_Query::get_sql('user','wp_users','ID'),
     * (provide your correct table name for wp_users) then calling $WP_Meta_Query::get_clauses(). The array keys in
     * the returned array are the correct keys to assign to the orderby argument.
     *
     * A perhaps easier way to get the proper keys is to instantiate WP_User_Query with your meta_query arguments,
     * then examining the SQL query in WP_User_Query::request. In particular the WHERE portion related to user meta.
     * The keys are the table aliases used in the SQL query. The first meta_query argument’s key is always the table
     * name, usually wp_usermeta unless you specified a different prefix in wp-config.php. Additional meta_query
     * arguments are assigned aliases like mt1, mt2, etc. The related meta key name occurs to the right of the = sign
     * in each case. Thus your orderby argument might be array('mt1', 'wp_usermeta',) or if you need differing order
     * arguments, array('mt1'=>'ASC', 'wp_usermeta'=>'DESC',)
     *
     * For reference, the user_meta part of the SQL WHERE clause may look something like this:
     * (( wp_usermeta.meta_key = 'foo' AND wp_usermeta.meta_value LIKE '%bar%' )
     * AND
     * ( mt1.meta_key = 'sna' AND mt1.meta_value LIKE '%fu%' ))
     * """""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""
     *
     * @param OrderByColumn|string $column
     * @param Direction $direction
     * @return $this
     */
    public function orderBy(OrderByColumn|string $column, Direction $direction = Direction::ascending): static
    {
        $this->arguments['orderby'][$column->value ?? $column] = $direction->value;

        return $this;
    }
}
