<?php

namespace Ipeweb\IpeSheets\Model;

use Ipeweb\IpeSheets\Model\Extendable\DataModel;
use Ipeweb\IpeSheets\Services\Validations;

class Card extends DataModel
{
    public function __construct(
        private int $id = -1,
        private int $theme_id = -1,
        private string $last_change = "",
        private string $synopsis = "",
        private string $imd = "",
    ) {
    }

    public function __set($attribute, $value)
    {
        Validations::validateSetProperty($this, $attribute, $value);

        $this->$attribute = $value;
    }

    public function __get($attribute)
    {
        Validations::validateGetProperty($this, $attribute);
        return $this->$attribute;
    }

    public function validate(?callable $callable = null): mixed
    {
        return parent::validate(
            function () {
                if ($this->synopsis == null or strlen($this->synopsis) <= 3 or strlen($this->synopsis) > 250) {
                    throw new \InvalidArgumentException("'{$this->synopsis}' is under the minimum size or excedes the max characters length");
                }

                return true;
            }
        );
    }

    public function toArray($class): array
    {
        return parent::toArray($this);
    }

    public function toString($object): string
    {
        return "";
    }
    public function toJson($class = null): string
    {
        return json_encode($this->toArray($this));
    }
}
