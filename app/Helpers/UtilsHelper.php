<?php

namespace App\Helpers;

class UtilsHelper
{
    /**
     * Retorna uma string somente com números (Ex: 0,12 = 012)
     *
     * @param string|integer|float $value
     * @return string
     */
    public static function onlyNumbers(string|int|float $value): string
    {
        return (string) preg_replace("/[^0-9]/", "", $value);
    }
}
