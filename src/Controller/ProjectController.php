<?php

namespace Ipeweb\RecapSheets\Controller;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\RecapSheets\Model\CardData;
use Ipeweb\RecapSheets\Model\NewProjectData;
use Ipeweb\RecapSheets\Model\ProjectUpdate;
use Ipeweb\RecapSheets\Model\UserProjectsData;
use Ipeweb\RecapSheets\Services\JWT;

class ProjectController
{
    public static function getUserProjects()
    {
        $field = isset($_GET['field']) ? $_GET['field'] : null;

        if (!$field) {
            http_response_code(400);
            return json_encode(["message" => 'Missing "field" in URL query']);
        }

        $preparedParams = [];
        if (str_contains($field, ':')) {
            $preparedParams = explode(':', $field);
            if (isset($preparedParams[0]) and isset($preparedParams[1])) {
                $projectService = new UserProjectsData;

                try {
                    http_response_code(200);
                    return $projectService->get([$preparedParams[0] => $preparedParams[1]]);
                } catch (\Throwable) {
                    http_response_code(500);
                    return json_encode(["message" => "Something went wrong on getting a user"]);
                }
            }
        }

        http_response_code(400);
        return json_encode(["message" => 'Invalid given "field" value. No key or value detected']);
    }

    public static function postNewProject()
    {
        $requestBody = Request::getBody();

        try {
            $projectService = new NewProjectData;
            http_response_code(200);
            return $projectService->insert($requestBody);
        } catch (\Throwable $e) {
            http_response_code(400);
            return json_encode(["message" => $e->getMessage()]);
        }
    }

    public static function updateProjectMd()
    {
        $requestBody = Request::getBody();
        $requestHeader = Request::getHeader();

        $projectUpdateService = new ProjectUpdate();

        try {
            $preparedData = $projectUpdateService->insert($requestBody);

            $requestToken = JWT::decode(str_replace('Bearer ', '', $requestHeader['Authorization']))[0];

            if (!isset($_GET['project_id'])) {
                http_response_code(400);
                var_dump($requestToken);
                return json_encode(["message" => "Invalid given body. No 'id' read on request body"]);
            }

            $userCanChange = new UserProjectsData();
            $result = $userCanChange->getSearch(['user_id' => $requestToken['id'], 'project_id' => $_GET['project_id']], 0, 1, strict: true);

            if (!empty($result) and $result[0]['user_permissions'] !== 'guest') {
                $cardService = new CardData();

                http_response_code(200);
                return $cardService->update($_GET['project_id'], $preparedData);
            }

            http_response_code(405);
            exit(json_encode(["message" => "This user is not allowed to change this project"]));
        } catch (MissingRequiredParameterException $missE) {
            http_response_code(400);
            exit(json_encode(["message" => $missE->getMessage()]));
        } catch (\Throwable $e) {
            http_response_code(500);
            exit(json_encode(["message" => "Something went wrong on updating the card:" . $e->getMessage()]));
        }
    }
}
