<?php

namespace IpeSheets\Tests;

use InvalidArgumentException;
use Ipeweb\IpeSheets\Exceptions\InvalidEmailException;
use Ipeweb\IpeSheets\Model\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private readonly User $user;
    public function setup(): void
    {
        $this->user = new User(
            5,
            "Fulano de Tal",
            "fulano_de_tal",
            "fulanodetal@email.com",
            "none",
            "pt-BR"
        );
    }

    public function testCantAssignValueToUnknownProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Can't assign value of unknown property: testNotExist");

        $this->user->testNotExist = "null";
    }

    public function testCantAssignDifferentValueTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Can't assign integer to a string value");

        $this->user->name = 2023;
    }

    public function testUserIdCannotBeChanged()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Property 'id' cannot be changed");

        $this->user->id = 1;
    }
    public function testEmailValidationOnUserCreate()
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage("Can't use 'invalidEmail' as a email");

        $this->user->email = "invalidEmail";
    }
}