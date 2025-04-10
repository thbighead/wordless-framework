<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Database\DTO\QueryResultDTO;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Application\Helpers\Database\Traits\SmartTransaction;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Helper;
use wpdb;

class Database extends Helper
{
    use SmartTransaction;

    /**
     * @return void
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    public static function defineWpConnectionConstants(): void
    {
        // ** MySQL settings - You can get this info from your web host ** //
        /** The name of the database for WordPress */
        define('DB_NAME', Environment::get('DB_NAME'));

        /** MySQL database username */
        define('DB_USER', Environment::get('DB_USER'));

        /** MySQL database password */
        define('DB_PASSWORD', Environment::get('DB_PASSWORD'));

        /** MySQL hostname */
        define('DB_HOST', Environment::get('DB_HOST'));

        /** Database Charset to use in creating database tables. */
        define('DB_CHARSET', Environment::get('DB_CHARSET', 'utf8'));

        /** The Database Collate type. Don't change this if in doubt. */
        define('DB_COLLATE', Environment::get('DB_COLLATE', 'utf8_general_ci'));
    }

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
