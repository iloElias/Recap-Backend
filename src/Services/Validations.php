<?php

namespace Ipeweb\IpeSheets\Services;

class Validations
{
    public static function validateSetProperty($classObj, string $property, $value = null)
    {
        if (!property_exists($classObj, $property)) {
            throw new \InvalidArgumentException("Can't assign value of unknown property: {$property}");
        }

        if (gettype($classObj->$property) != gettype($value)) {
            $valueType = gettype($value);
            $propertyType = gettype($classObj->$property);

            throw new \InvalidArgumentException("Can't assign {$valueType} to a {$propertyType} value");
        }
    }

    public static function validateGetProperty($classObj, string $property)
    {
        if (!property_exists($classObj, $property)) {
            $className = $classObj::class;
            throw new \InvalidArgumentException("Unknown property '{$property}' in '{$className}' class");
        }
    }

    public static function validateMethod(object $classObj, string $method)
    {
        if (!method_exists($classObj, $method)) {
            $className = $classObj::class;
            throw new \InvalidArgumentException("Unknown method '{$method}' in '{$className}' class");
        }
    }
}
