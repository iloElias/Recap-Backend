<?php

namespace Ipeweb\IpeSheets\Model;

use Ipeweb\IpeSheets\Model\Extendable\DataModel;
use Ipeweb\IpeSheets\Services\Validations;

class Project extends DataModel
{
    public function __construct(
        private int $id = -1,
        private int $card_id = -1,
        private string $name = '',
        private string $type = '',
        private bool $is_active = true,
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
                if ($this->name == null or strlen($this->name) <= 3) {
                    throw new \InvalidArgumentException("'{$this->name}' is not a valid name");
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
