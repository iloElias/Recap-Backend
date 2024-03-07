<?php

namespace Ipeweb\RecapSheets\Middleware;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Model\QueryGet;
use Ipeweb\RecapSheets\Model\UserProjectsData;

class ValidateDeletePermission implements Middleware
{
    public static function handle($request)
    {
        $query = QueryGet::getQueryItems(["user_id" => true, "project_id" => true]);

        try {
            $userProjectService = new UserProjectsData();
            $searchResult = $userProjectService->getSearch(["user_id" => Request::$decodedToken['id'], "project_id" => $query["project_id"]], 0, 1, null, true);

            if (!$searchResult[0] || $searchResult[0]['user_permissions'] === "guest") {
                http_response_code(403);
                exit(json_encode([
                    'message' => 'This user has no permission to perform this action'
                ]));
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            exit(json_encode([
                'message' => 'Something went wrong on validating user permission: ' . $e->getMessage()
            ]));
        }
    }
}
