<?php

namespace BehatTest\Utils;

use RuntimeException;

class Json
{
    /**
     * @param string $jsonString
     *
     * @param bool $assoc
     *
     * @return mixed
     */
    public static function decode(string $jsonString, bool $assoc = true)
    {
        $array = json_decode($jsonString, $assoc);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = json_last_error();
            $message = json_last_error_msg();

            throw new RuntimeException('Error decode json data. Code: ' . $error . '; Message: ' . $message);
        }

        return $array;
    }

    /**
     * @param array $array
     *
     * @return string
     */
    public static function prettyEncode(array $array): string
    {
        $jsonString = json_encode($array, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES);
        if (json_last_error() !== JSON_ERROR_NONE) {
            var_dump($array);
            $error = json_last_error();
            $message = json_last_error_msg();

            throw new RuntimeException('Error decode json data. Code: ' . $error . '; Message: ' . $message);
        }

        return $jsonString;
    }
}
