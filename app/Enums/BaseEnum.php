<?php

namespace App\Enums;

use ReflectionClass;

class BaseEnum
{
    /**
     * Returns all constants into array
     *
     * @return array
     */
    public static function toArray(): array
    {
        return (new ReflectionClass(static::class))->getConstants();
    }

    /**
     * Return all constants keys into array
     *
     * @return void
     */
    public static function toArrayKeys(): array
    {
        return array_keys(self::toArray());
    }

    /**
     * Return all constants values
     *
     * @param mixed $value
     * @return void
     */
    public static function getConstant($value)
    {
        return (new ReflectionClass(static::class))->getConstant(strtoupper($value));
    }
}
