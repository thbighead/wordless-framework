<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Database\DTO\QueryResultDTO;
use Wordless\Application\Helpers\Database\Exceptions\InvalidDataTypeToInsert;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Application\Helpers\Database\Traits\SmartTransaction;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
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
     * @param string $table
     * @param array<string, double|int|string> $data
     * @return QueryResultDTO
     * @throws InvalidDataTypeToInsert
     * @throws QueryError
     */
    public static function insert(string $table, array $data): QueryResultDTO
    {
        if (($affected_rows = self::wpdb()->insert(
                Str::startWith($table, self::wpdb()->prefix),
                $data,
                self::parseDataTypes($data)
            )) === false) {
            throw new QueryError(self::wpdb()->last_query);
        }

        return new QueryResultDTO($affected_rows, self::wpdb()->last_result);
    }

    /**
     * @param string $query
     * @return QueryResultDTO
     * @throws QueryError
     */
    public static function query(string $query): QueryResultDTO
    {
        if (($affected_rows = self::wpdb()->query($query)) === false) {
            throw new QueryError($query);
        }

        return new QueryResultDTO($affected_rows, self::wpdb()->last_result);
    }

    final public static function wpdb(): wpdb
    {
        global $wpdb;

        return $wpdb;
    }

    /**
     * @param array<string, double|int|string> $data
     * @return array
     * @throws InvalidDataTypeToInsert
     */
    private static function parseDataTypes(array $data): array
    {
        $data_types = [];

        foreach ($data as $key => $value) {
            $data_types[] = match ($type = GetType::of($value)) {
                GetType::DOUBLE => '%f',
                GetType::INTEGER => '%d',
                GetType::STRING => '%s',
                default => throw new InvalidDataTypeToInsert($type, $key, $value),
            };
        }

        return $data_types;
    }
}
