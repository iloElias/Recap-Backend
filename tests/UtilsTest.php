<?php

namespace Tests\Unit\Services;

use Ipeweb\RecapSheets\Services\Utils;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ipeweb\RecapSheets\Services\Utils
 */
class UtilsTest extends TestCase
{
    public function testArrayFind()
    {
        $array = [1, 2, 3, 4, 5];
        $searchValue = 3;
        $result = Utils::arrayFind($array, $searchValue);
        $this->assertTrue($result);

        $array = [1, 2, 4, 5];
        $searchValue = 3;
        $result = Utils::arrayFind($array, $searchValue);
        $this->assertFalse($result);

        $array = [];
        $searchValue = 3;
        $result = Utils::arrayFind($array, $searchValue);
        $this->assertFalse($result);
    }

    public function testStrRemoveLast()
    {
        $string = "hello";
        $result = Utils::strRemoveLast($string);
        $this->assertEquals("hell", $result);

        $string = "h";
        $result = Utils::strRemoveLast($string);
        $this->assertEquals("", $result);

        $string = "";
        $result = Utils::strRemoveLast($string);
        $this->assertEquals("", $result);
    }
}
