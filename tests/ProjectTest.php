<?php

namespace Tests\Unit\Model\Template;

use Ipeweb\RecapSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\RecapSheets\Model\Template\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    public function testValidate()
    {
        $project = new Project();

        $params = ['user_id' => 1];
        $this->expectException(MissingRequiredParameterException::class);
        $project->validate($params);

        $params = ['user_id' => 1, 'name' => 'Project Name', 'synopsis' => 'Project Synopsis'];
        $result = $project->validate($params);
        $this->assertEmpty($result);
    }

    public function testPrepare()
    {
        $project = new Project();

        $params = ['user_id' => 1, 'name' => 'Project Name', 'synopsis' => 'Project Synopsis'];
        $result = $project->prepare($params);
        $this->assertEquals($params, $result);

    }

    public function testInsert()
    {
        $project = new Project();

        $params = ['user_id' => 1];
        $this->expectException(MissingRequiredParameterException::class);
        $project->insert($params);

        $params = ['user_id' => 1, 'name' => 'Project Name', 'synopsis' => 'Project Synopsis'];
        $result = $project->insert($params);
        $this->assertEquals($params, $result);
    }

    public function testUpdate()
    {
        $project = new Project();

        $params = ['user_id' => 1];
        $this->expectException(MissingRequiredParameterException::class);
        $project->update($params);

        $params = ['user_id' => 1, 'name' => 'Project Name', 'synopsis' => 'Project Synopsis'];
        $result = $project->update($params);
        $this->assertEquals($params, $result);
    }

    public function testDelete()
    {
        $project = new Project();

        $params = ['user_id' => 1];
        $this->expectException(MissingRequiredParameterException::class);
        $project->delete($params);

        $params = ['user_id' => 1, 'name' => 'Project Name', 'synopsis' => 'Project Synopsis'];
        $result = $project->delete($params);
        $this->assertEquals($params, $result);
    }
}