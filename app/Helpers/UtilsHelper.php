<?php

namespace App\Helpers;

class UtilsHelper
{
    /**
     * Returns a numeric string, ex: "01,23.4" => "01234"
     *
     * @param string|integer|float $value
     * @return string
     */
    public static function onlyNumbers(string|int|float $value): string
    {
        return (string) preg_replace("/[^0-9]/", "", $value);
    }

    /**
     * Validate if string is json and returns array
     *
     * @param string $string
     * @return array|null
     */
    public static function isJson(string $string): ?array
    {
        $json = json_decode($string, true);

        return json_last_error() === JSON_ERROR_NONE && is_array($json)
            ? $json
            : null;
    }
}
