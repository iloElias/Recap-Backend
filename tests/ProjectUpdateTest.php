<?php

use Ipeweb\RecapSheets\Model\Template\ProjectUpdate;
use PHPUnit\Framework\TestCase;

class ProjectUpdateTest extends TestCase
{

    public function testStoreString()
    {
        $projectUpdate = new ProjectUpdate();

        $result = $projectUpdate->storeString('');
        $this->assertEquals('', $result);

        $originalString = "This is a test string with 'quotes' and \"double quotes\".";
        $expectedResult = "This is a test string with &1qt;quotes&1qt; and &2qt;double quotes&2qt;.";
        $result = $projectUpdate->storeString($originalString);
        $this->assertEquals($expectedResult, $result);
    }

    public function testRestoreString()
    {
        $projectUpdate = new ProjectUpdate();

        $result = $projectUpdate->restoreString('');
        $this->assertEquals('', $result);

        $storedString = "This is a test string with &1qt;quotes&1qt; and &2qt;double quotes&2qt;.";
        $expectedResult = "This is a test string with 'quotes' and \"double quotes\".";
        $result = $projectUpdate->restoreString($storedString);
        $this->assertEquals($expectedResult, $result);
    }

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

        $this->assertEquals("This is a test\\n'Single quote' test\\n\"Double quote\" test\\n", $testResult);
    }
}
