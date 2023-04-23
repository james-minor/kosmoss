<?php

namespace Kosmoss\FileUtilities;

use Exception;
use Psr\Log\InvalidArgumentException;

use Kosmoss;
use DateTime;

/**
 * Kosmoss logger that follows the PSR-3 Logger Interface documentation described here:
 * https://www.php-fig.org/psr/psr-3/
 */
class LoggingManager
{
    /**
     * Log detailed debug information.
     *
     * @param string $message   The message to log.
     * @param array $context    Extra information that does not fit well in a string.
     * See https://www.php-fig.org/psr/psr-3/#13-context for more information.
     * @return void
     */
    public static function debug(string $message, array $context = array()): void
    {
        self::log('debug', $message, $context);
    }

    /**
     * Log events, (a user logging in, SQL logs, etc).
     *
     * @param string $message   The message to log.
     * @param array $context    Extra information that does not fit well in a string.
     * See https://www.php-fig.org/psr/psr-3/#13-context for more information.
     * @return void
     */
    public static function info(string $message, array $context = array()): void
    {
        self::log('info', $message, $context);
    }

    /**
     * Log normal, but significant events.
     *
     * @param string $message   The message to log.
     * @param array $context    Extra information that does not fit well in a string.
     * See https://www.php-fig.org/psr/psr-3/#13-context for more information.
     * @return void
     */
    public static function notice(string $message, array $context = array()): void
    {
        self::log('notice', $message, $context);
    }

    /**
     * Log exceptional occurrences that are NOT errors.
     *
     * @param string $message   The message to log.
     * @param array $context    Extra information that does not fit well in a string.
     * See https://www.php-fig.org/psr/psr-3/#13-context for more information.
     * @return void
     */
    public static function warning(string $message, array $context = array()): void
    {
        self::log('warning', $message, $context);
    }

    /**
     * Log runtime errors that do not require immediate action.
     *
     * @param string $message   The message to log.
     * @param array $context    Extra information that does not fit well in a string.
     * See https://www.php-fig.org/psr/psr-3/#13-context for more information.
     * @return void
     */
    public static function error(string $message, array $context = array()): void
    {
        self::log('error', $message, $context);
    }

    /**
     * Log critical condition (example: unexpected exception).
     *
     * @param string $message   The message to log.
     * @param array $context    Extra information that does not fit well in a string.
     * See https://www.php-fig.org/psr/psr-3/#13-context for more information.
     * @return void
     */
    public static function critical(string $message, array $context = array()): void
    {
        self::log('critical', $message, $context);
    }

    /**
     * Log information and notify administration that action that must be taken immediately.
     *
     * @param string $message   The message to log.
     * @param array $context    Extra information that does not fit well in a string.
     * See https://www.php-fig.org/psr/psr-3/#13-context for more information.
     * @return void
     */
    public static function alert(string $message, array $context = array()): void
    {
        self::log('alert', $message, $context);
    }

    /**
     * Log that the system is in an unusable state.
     *
     * @param string $message   The message to log.
     * @param array $context    Extra information that does not fit well in a string.
     * See https://www.php-fig.org/psr/psr-3/#13-context for more information.
     * @return void
     */
    public static function emergency(string $message, array $context = array()): void
    {
        self::log('emergency', $message, $context);
    }

    /**
     * @param mixed $level      The log level to log the message at.
     * @param string $message   The message to log.
     * @param array $context    Extra information that does not fit well in a string.
     * See https://www.php-fig.org/psr/psr-3/#13-context for more information.
     * @return void
     */
    public static function log(mixed $level, string $message, array $context = array()): void
    {
        // Validating the log level.
        $validLogLevel = match($level)
        {
            'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug' => true,
            default => false
        };
        if(!$validLogLevel)
        {
            throw new InvalidArgumentException('Invalid logLevel: ' . $level);
        }

        // Getting the logging file path.
        $dateTime = new DateTime();
        $filePath = ROOT_DIRECTORY . '\logs\\' . $dateTime->format('m-d-Y') . '.txt';

        // Writing to the log file.
        $file = fopen($filePath, 'a');
        if($file)
        {
            $messagePrefix = '[' . $dateTime->format('m-d-Y H:i:s') . '] [' . $level . ']: ';

            fwrite($file, $messagePrefix . $message . PHP_EOL);  // Writing the base level log message.

            // Parsing the exception key in the context array.
            if(key_exists('exception', $context))
            {
                if(!($context['exception'] instanceof Exception))
                {
                    throw new InvalidArgumentException(
                        'The "exception" key in the context array MUST be an instance of the Exception class.'
                    );
                }

                // Writing the exception data.
                fwrite($file, $messagePrefix . $context['exception']->getMessage() . PHP_EOL);
                fwrite($file, $messagePrefix . $context['exception']->getTraceAsString() . PHP_EOL);

                // Removing the exception from the context array.
                unset($context['exception']);
            }

            // Parsing the rest of the keys in the context array.
            foreach($context as $item)
            {
                fwrite($file, $messagePrefix . 'context["' .key($item) . '"] = ' . $item . PHP_EOL);
            }

            fclose($file);
        }
    }
}