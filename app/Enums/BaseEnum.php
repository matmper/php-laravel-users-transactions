<?php

namespace App\Enums;

use ReflectionClass;

class BaseEnum
{
    /**
     * Retorna todos as constantes em array
     *
     * @return array
     */
    public static function toArray(): array
    {
        return (new ReflectionClass(static::class))->getConstants();
    }

    /**
     * Transforma a array keys em array
     *
     * @return void
     */
    public static function toArrayKeys(): array
    {
        return array_keys(self::toArray());
    }

    /**
     * Retorna o valor da constante
     *
     * @param mixed $value
     * @return void
     */
    public static function getConstant($value)
    {
        return (new ReflectionClass(static::class))->getConstant(strtoupper($value));
    }
}
