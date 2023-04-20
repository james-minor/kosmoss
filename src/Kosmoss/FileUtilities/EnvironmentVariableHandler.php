<?php

namespace Kosmoss\FileUtilities;

class EnvironmentVariableHandler
{
    /**
     * Parses data from a .env file into a string array. Parses out comments, empty lines, etc.
     *
     * @param string	$filePath		The file path of the .env file.
     *
     * @return array                    Will return a 1D array with the Key-Value pairs from the .env file. If the
     * file could not be found will return an empty array.
     */
    static function parseENVFile(string $filePath): array
    {
        // Reading SQL connection data from the .env file.
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
     * Writes a key-value pair to the .env file
     *
     * @param string $filePath      The file path of the .env file. Will attempt to create the .env file if it does
     * not exist.
     * @param string $key           The key to update, if the key is <b>not present</b> will append the key to end of
     * the file.
     * @param string $value         The value to give the passed key.
     * @return void
     */
    static function writeENVFile(string $filePath, string $key, string $value): void
    {
        // Getting the original .env file data (if it exists).
        if(file_exists($filePath))
        {
            $envFileData = explode(PHP_EOL, file_get_contents($filePath));
        }
        else
        {
            $envFileData = '';
        }

        // Iterating over the old file data and overwriting the key-value pair.
        for($i = 0; $i < count($envFileData); $i++)
        {
            $pair = self::parseLine($envFileData[$i]);
            if($pair and $pair[0] == $key)
            {
                $envFileData[$i] = $key . '=' . $value . PHP_EOL;
                break;
            }
        }

        // If the key was not found in the .env file data, append the key-value pair to the end of the file.
        if($i == count($envFileData))
        {
            $envFileData[] = $key . '=' . $value . PHP_EOL;
        }

        file_put_contents($filePath, implode(PHP_EOL, $envFileData));
    }

    /**
     * Parses a line from a .env file into a key-value pair.
     *
     * @param string $line      The .env line to parse.
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