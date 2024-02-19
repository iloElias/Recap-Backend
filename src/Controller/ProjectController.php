<?php

namespace Ipeweb\RecapSheets\Controller;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Model\NewProjectData;
use Ipeweb\RecapSheets\Model\UserProjectsData;

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
}
