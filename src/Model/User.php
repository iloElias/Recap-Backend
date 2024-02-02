<?php

namespace Ipeweb\IpeSheets\Model;

use Ipeweb\IpeSheets\Exceptions\InvalidEmailException;
use Ipeweb\IpeSheets\Model\Extendable\DataModel;
use Ipeweb\IpeSheets\Services\Email;
use Ipeweb\IpeSheets\Services\Validations;

class User extends DataModel
{
    public function __construct(
        private int $id = -1,
        private string $name = '',
        private string $username = '',
        private string $email = '',
        private string $picture_path = '',
        private string $preferred_lang = '',
        private bool $is_active = true,
    ) {
    }

    public function __set($attribute, $value)
    {
        Validations::validateSetProperty($this, $attribute, $value);

        switch ($attribute) {
            case ("email"):
                if (!Email::validate($value)) {
                    throw new InvalidEmailException("Can't use '{$value}' as a email");
                }
                break;

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
                if ($this->name == null or strlen($this->name) <= 2) {
                    throw new \InvalidArgumentException("'{$this->name}' is not a valid name");
                }
                if ($this->username == null or strlen($this->username) <= 4) {
                    throw new \InvalidArgumentException("'{$this->username}' is too short to be used as a username");
                }
                if (!Email::validate($this->email)) {
                    throw new InvalidEmailException("'{$this->email}' cannot be used as a email");
                }
                if ($this->preferred_lang == null) {
                    $this->preferred_lang == "en";
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
