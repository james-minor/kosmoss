<?php

namespace Kosmoss\FileUtilities;

use Kosmoss;
use DateTime;

class LogManager
{
    /**
     * Logs debug information into the log file for the current day.
     *
     * @param string $info      The debug information to log.
     * @return void
     */
    static function logInfo(string $info): void
    {
        self::log($info, 'INFO');
    }

    /**
     * Logs warning information into the log file for the current day.
     *
     * @param string $info      The warning information to log.
     * @return void
     */
    static function logWarning(string $info): void
    {
        self::log($info, 'WARN');
    }

    /**
     * Logs error information into the log file for the current day.
     *
     * @param string $info      The error information to log.
     * @return void
     */
    static function logError(string $info): void
    {
        self::log($info, 'ERROR');
    }

    /**
     * The internal logging method that will log data to the current day's logging file.
     *
     * @param string $info      The information to log.
     * @param string $type      The type of the information, (for example: DEBUG, WARN, or ERROR).
     * @return void
     */
    static private function log(string $info, string $type): void
    {
        $dateTime = new DateTime();
        $filePath = ROOT_DIRECTORY . '\logs\\' . $dateTime->format('m-d-Y') . '.txt';

        // Writing to the log file.
        $logFile = fopen($filePath, 'a');
        if($logFile)
        {
            fwrite($logFile, '[' . $dateTime->format('m-d-Y H:i:s') . '] [' . $type . ']: ' . $info . "\n");
        }
        else
        {
            echo 'http 500';
        }
    }
}