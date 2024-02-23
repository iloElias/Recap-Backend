<?php

namespace Ipeweb\RecapSheets\Model\Template;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\RecapSheets\Model\Abstracts\CrudAbstract;
use Ipeweb\RecapSheets\Model\CardData;
use Ipeweb\RecapSheets\Model\ProjectData;
use Ipeweb\RecapSheets\Model\UserProjectsData;

class ProjectUpdate extends CrudAbstract
{
    public static array $requiredFields = ["imd"];

    public function update(array $params)
    {
        if (!isset($_GET['project_id'])) {
            http_response_code(400);
            return json_encode(["message" => "Invalid given body. No 'id' read on request body"]);
        }

        $userCanChange = new UserProjectsData();
        $result = $userCanChange->getSearch(['user_id' => Request::$decodedToken['id'], 'project_id' => $_GET['project_id']], strict: true);

        if (!empty($result) and $result[0]['user_permissions'] !== 'guest') {
            $projectService = new ProjectData();
            $projectResult = $projectService->getSearch(['id' => $_GET['project_id']], strict: true);

            if (!empty($projectResult)) {
                $cardService = new CardData();

                http_response_code(200);
                return $cardService->update($projectResult[0]['card_id'], $this->process($params));
            }
        } else if (empty($result)) {
            http_response_code(404);
            exit(json_encode(["message" => "No project found with the given id"]));
        }
    }

    public function validate(array $params)
    {
        $missingList = [];
        foreach (self::$requiredFields as $field) {
            if (!array_key_exists($field, $params)) {
                $missingList[] = $field;
            }
        }
        if (!empty($missingList)) {
            throw new MissingRequiredParameterException($missingList);
        }
    }

    public function prepare(array $params)
    {
        $preparedStr = str_replace("\"", '&2asp;', $params['imd']);
        $preparedStr = str_replace("'", '&1asp;', $preparedStr);

        $params['imd'] = $preparedStr;

        return $params;
    }
}
