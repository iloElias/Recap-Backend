<?php

namespace Tests\Unit\Model\Template;

use Ipeweb\RecapSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\RecapSheets\Model\Template\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testValidate()
    {
        $user = new User();

        $params = ['google_id' => '123', 'email' => 'test@example.com'];
        $this->expectException(MissingRequiredParameterException::class);
        $user->validate($params);

        $params = ['google_id' => '123', 'name' => 'Test User', 'email' => 'test@example.com'];
        $result = $user->validate($params);
        $this->assertEmpty($result);
    }

    public function testPrepare()
    {
        $user = new User();

        $params = ['google_id' => '123', 'name' => 'Test User', 'email' => 'test@example.com'];
        $expectedResult = $params;
        $expectedResult['logged_in'] = date('Y-m-d H:i:s');
        $result = $user->prepare($params);
        $this->assertEquals($expectedResult, $result);
    }

    public function testInsert()
    {
        $user = new User();

        $params = ['google_id' => '123', 'email' => 'test@example.com'];
        $this->expectException(MissingRequiredParameterException::class);
        $user->insert($params);

        $params = ['google_id' => '123', 'name' => 'Test User', 'email' => 'test@example.com'];
        $result = $user->insert($params);
        $this->assertEquals($params, $result);
    }

    public function testUpdate()
    {
        $user = new User();

        $params = ['google_id' => '123', 'email' => 'test@example.com'];
        $this->expectException(MissingRequiredParameterException::class);
        $user->update($params);

        $params = ['google_id' => '123', 'name' => 'Test User', 'email' => 'test@example.com'];
        $result = $user->update($params);
        $this->assertEquals($params, $result);
    }

    public function testDelete()
    {
        $user = new User();

        $params = ['google_id' => '123', 'email' => 'test@example.com'];
        $this->expectException(MissingRequiredParameterException::class);
        $user->delete($params);

        $params = ['google_id' => '123', 'name' => 'Test User', 'email' => 'test@example.com'];
        $result = $user->delete($params);
        $this->assertEquals($params, $result);
    }
}
