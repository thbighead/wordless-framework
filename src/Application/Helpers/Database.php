<?php

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Database\DTO\QueryResultDTO;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Application\Helpers\Database\Traits\SmartTransaction;
use Wordless\Infrastructure\Helper;
use wpdb;

class Database extends Helper
{
    use SmartTransaction;

    /**
     * @param string $query
     * @return QueryResultDTO
     * @throws QueryError
     */
    public static function query(string $query): QueryResultDTO
    {
        /** @var wpdb $wpdb */
        global $wpdb;

        if (($affected_rows = $wpdb->query($query)) === false) {
            throw new QueryError($query);
        }

        return new QueryResultDTO($affected_rows, $wpdb->last_result);
    }
}
