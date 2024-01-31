<?php

namespace Ipeweb\IpeSheets\Model\Extendable;

use Ipeweb\IpeSheets\Model\Interfaces\Arrayable;
use Ipeweb\IpeSheets\Model\Interfaces\Serializable;
use Ipeweb\IpeSheets\Model\Interfaces\Validatable;

class DataModel implements Arrayable, Serializable, Validatable
{

    public function toArray($class): array
    {
        $objArray = [];
        $objClass = new $class;
        foreach (get_class_vars($objClass::class) as $key => $value) {
            $objArray[$key] = $objClass->$key;
        }

        return $objArray;
    }

    public function toJson($object): string
    {
        return json_encode($this->toArray($object));
    }

    public function toString($object): string
    {
        $objArray = $this->toArray($object);
        $finalString = $object::class . "has the values:
        ";

        foreach ($objArray as $key => $value) {
            $finalString .= "{$key} as {$value}";
        }

        return $finalString;
    }

    public function validate(callable $callable): mixed
    {
        return $callable();
    }
}
