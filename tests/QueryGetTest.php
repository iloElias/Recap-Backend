<?php

namespace Tests\Unit\Model;

use Ipeweb\RecapSheets\Model\QueryGet;
use PHPUnit\Framework\TestCase;

class QueryGetTest extends TestCase
{
    public function testGetQueryItemsWithRequiredParamsProvided()
    {
        $query = ['param1' => 'value1', 'param2' => 'value2'];

        $requiredList = ['param1' => true, 'param2' => true];
        $result = QueryGet::getQueryItems($requiredList, $query);

        $this->assertEquals(['param1' => 'value1', 'param2' => 'value2'], $result);
    }

    public function testGetQueryItemsWithMissingRequiredParams()
    {
        $query = ['param1' => 'value1'];

        $requiredList = ['param1' => true, 'param2' => true];
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Query param2 item not provided, which is required");

        QueryGet::getQueryItems($requiredList, $query);
    }

    public function testGetQueryItemsWithEmptyGetParams()
    {
        $query = [];

        $requiredList = ['param1' => true, 'param2' => true];
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Query param1 item not provided, which is required");

        QueryGet::getQueryItems($requiredList, $query);
    }
}
