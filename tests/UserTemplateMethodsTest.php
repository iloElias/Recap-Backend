<?php

use Ipeweb\RecapSheets\Model\Template\User;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ipeweb\RecapSheets\Model\Template\User
 */
class UserTemplateMethodsTest extends TestCase
{
    public function testLastTimeUpdate()
    {
        $userService = new User();
        $resultTime = $userService->prepare([])["logged_in"];

        $currentTime = date('Y-m-d H:i:s');

        $this->assertEquals($currentTime, $resultTime);
    }
}
