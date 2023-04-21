<?php

namespace Kosmoss\FileUtilities;

class EnvironmentFileHandler
{
    /**
     * Parses data from a environment variable file into a string array. Parses out comments, empty lines, etc.
     *
     * @param string	$filePath		The file path of the environment variable file.
     *
     * @return array                    Will return a 1D array with the Key-Value pairs from the environment variable file. If the
     * file could not be found will return an empty array.
     */
    static function parseENVFile(string $filePath): array
    {
        // Reading SQL connection data from the environment variable file.
        $environmentVariables = array();
        $envFile = fopen($filePath, 'r');

        if($envFile)
        {
            while(!feof($envFile))
            {
                $pair = self::parseLine(fgets($envFile));
                if($pair)
                {
                    $environmentVariables[$pair[0]] = $pair[1];
                }
            }

            fclose($envFile);
        }

        return $environmentVariables;
    }

    /**
     * Writes an environment variable file from a passed 1D array of key-value pairs.
     *
     * @param string $filePath      The file path of the environment variable file. Will overwrite the file if it
     * already exists.
     * @param array $pairs          The key-value pairs to write to the environment variable file. <b>NOTE:</b> The
     * value of each key in the array must be of type: boolean, integer, double, or string. Any other type will NOT be
     * written to the environment variable file.
     * @return void
     */
    static function writeENVFile(string $filePath, array $pairs): void
    {
        $envFile = fopen($filePath, 'w');

        if($envFile)
        {
            for($i = 0; $i < count($pairs); $i++)
            {
                // Validating that the value is of a valid type.
                $validType = match(gettype($pairs[$i]))
                {
                    "boolean", "integer", "double", "string" => true,
                    default => false
                };

                // Writing to the environment variable file.
                if($validType)
                {
                    fwrite($envFile, key($pairs[$i]) . '=' . $pairs[$i] . PHP_EOL);
                }
            }

            fclose($envFile);
        }
    }

    /**
     * Parses a line from an environment variable file into a key-value pair.
     *
     * @param string $line      The environment variable line to parse.
     * @return array|null       Returns an array of length 2 that contains the key and value respectively. If the line
     * has invalid syntax, is empty, or a commented line, then returns null.
     */
    protected static function parseLine(string $line): ?array
    {
        // Removing spaces from current line.
        $line = str_replace(' ', '', $line);

        // Skipping empty lines and comments.
        if(empty($line) or $line[0] === '#')
        {
            return null;
        }

        // Stripping both UNIX and Windows new-line characters from the current line.
        $line = str_replace("\n", '', $line);
        $line = str_replace("\r", '', $line);

        // Splitting the line into a key-value pair.
        if($keyValuePair = explode('=', $line) and count($keyValuePair) == 2)
        {
            return $keyValuePair;
        }

        return null;
    }
}