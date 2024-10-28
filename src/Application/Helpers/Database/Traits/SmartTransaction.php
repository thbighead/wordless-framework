<?php

namespace Wordless\Application\Helpers\Database\Traits;

use Closure;
use Exception;
use Wordless\Application\Helpers\Database\DTO\QueryResultDTO;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Application\Helpers\Str;

trait SmartTransaction
{
    private static array $transaction_save_points = [];

    /**
     * @param Closure $script
     * @return mixed
     * @throws QueryError
     */
    public static function smartTransaction(Closure $script): mixed
    {
        self::startSmartTransaction();

        try {
            $script_return = $script();
        } catch (Exception $exception) {
            self::rollbackSmartTransaction();
            throw $exception;
        }

        self::commitSmartTransaction();

        return $script_return;
    }

    /**
     * @return bool
     * @throws QueryError
     */
    public static function commitSmartTransaction(): bool
    {
        if (self::anySavePointsExists()) {
            array_pop(self::$transaction_save_points);

            return true;
        }

        if (self::isTransactionOpened()) {
            self::query('COMMIT');

            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws QueryError
     */
    public static function rollbackSmartTransaction(): bool
    {
        if (self::anySavePointsExists()) {
            $save_point_id = array_pop(self::$transaction_save_points);

            self::query("ROLLBACK TO $save_point_id");

            return true;
        }

        if (self::isTransactionOpened()) {
            self::query('ROLLBACK');

            return true;
        }

        return false;
    }

    /**
     * @return void
     * @throws QueryError
     */
    public static function startSmartTransaction(): void
    {
        if (self::isSmartTransactionOpened()) {
            $save_point_id = Str::uuid();

            self::query("SAVEPOINT $save_point_id");

            self::$transaction_save_points[] = $save_point_id;
            return;
        }

        self::query('START TRANSACTION');
    }

    /**
     * @return bool
     * @throws QueryError
     */
    private static function isSmartTransactionOpened(): bool
    {
        return self::anySavePointsExists() || self::isTransactionOpened();
    }

    /**
     * @return bool
     * @throws QueryError
     */
    private static function isTransactionOpened(): bool
    {
        return self::query('SHOW VARIABLES LIKE "in_transaction"')->results?->Value ?? false;
    }

    private static function anySavePointsExists(): bool
    {
        return !empty(self::$transaction_save_points);
    }
}
