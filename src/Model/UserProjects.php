<?php

namespace Ipeweb\IpeSheets\Model;

use Ipeweb\IpeSheets\Model\Extendable\DataModel;
use Ipeweb\IpeSheets\Services\Validations;

class UserProjects extends DataModel
{
    public function __construct(
        private int $user_id = -1,
        private int $project_id = -1,
        private string $user_permissions = "",
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
                if ($this->user_permissions == null) {
                    $this->user_permissions == "Own";
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
        return parent::toString($this);
    }
    public function toJson($class = null): string
    {
        return json_encode($this->toArray($this));
    }
}