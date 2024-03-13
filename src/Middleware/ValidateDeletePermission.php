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
            $userProjectsData = new UserProjectsData();
            $searchResult = $userProjectsData->getSearch(["user_id" => Request::$decodedToken['id'], "project_id" => $query["project_id"]], 0, 1, null, true);

            if (!$searchResult[0] || $searchResult[0]['user_permissions'] === "guest") {
                http_response_code(403);
                throw new \Exception('This user has no permission to perform this action');
            }
        } catch (\Throwable $throwable) {
            http_response_code(500);
            throw new \Exception('Something went wrong on validating user permission: ' . $throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }
}
