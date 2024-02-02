<?php

use Ipeweb\IpeSheets\Exceptions\InvalidEmailException;
use Ipeweb\IpeSheets\Model\Extendable\DataModel;
use Ipeweb\IpeSheets\Services\Validations;

class UserProjects extends DataModel
{
    public function __construct(
        private int $user_id = -1,
        private int $project_id = -1,
        private string $user_permission = "",
    ) {
    }

    public function __set($attribute, $value)
    {
        Validations::validateSetProperty($this, $attribute, $value);

        switch ($attribute) {
            case ("user_id"):
                throw new \InvalidArgumentException("Property 'id' cannot be changed");

            case ("project_id"):
                throw new \InvalidArgumentException("Property 'id' cannot be changed");


            default:
                break;
        }

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
                if ($this->user_permission == null) {
                    $this->user_permission == "Own";
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
