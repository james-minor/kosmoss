<?php

namespace Kosmoss\Database;

use Kosmoss\FileUtilities\EnvironmentFileHandler;
use Kosmoss\FileUtilities\LogManager;

use mysqli;
use mysqli_sql_exception;

class SQLConnectionHandler
{
    static ?mysqli $sqlConnection = null;

    /**
     * Attempts to connect to the backend SQL database using the credentials found in the .env file.
     *
     * @return bool     Returns <b>true</b> if a connection to the database was successfully established,
     * otherwise returns <b>false</b>.
     */
    static function connect(): bool
    {
        // Attempting to initialize a database connection.
        $environmentVariables = EnvironmentFileHandler::parseENVFile('.env');
        try
        {
            self::$sqlConnection = new mysqli(
                $environmentVariables['SQL_HOSTNAME'],
                $environmentVariables['SQL_USERNAME'],
                $environmentVariables['SQL_PASSWORD'],
                $environmentVariables['SQL_DATABASE']
            );
            return true;
        }
        catch(mysqli_sql_exception $exception)
        {
            LogManager::logError($exception->getMessage());

            self::$sqlConnection = null;
            return false;
        }
    }


}