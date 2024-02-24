<?php

use Ipeweb\RecapSheets\Model\Template\ProjectUpdate;
use PHPUnit\Framework\TestCase;

class ProjectUpdateTest extends TestCase
{
    public function testTranscribeMarkdown(): void
    {
        $params['imd'] = "This is a test\\n\\'Single quote\\' test\\n\"Double quote\" test\\n";

        $projectService = new ProjectUpdate();
        $testResult = $projectService->prepare($params)['imd'];

        $this->assertEquals("This is a test&nln;&1qt;Single quote&1qt; test&nln;&2qt;Double quote&2qt; test&nln;", $testResult);
    }

    public function testTranscribeSavedMarkdown(): void
    {
        $params['imd'] = "This is a test&nln;&1qt;Single quote&1qt; test&nln;&2qt;Double quote&2qt; test&nln;";

        $projectService = new ProjectUpdate();
        $testResult = $projectService->restoreString($params['imd']);

        $this->assertEquals("This is a test\\n\\'Single quote\\' test\\n\\\"Double quote\\\" test\\n", $testResult);
    }
}
