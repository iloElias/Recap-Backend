<?php

namespace Ipeweb\RecapSheets\Controller;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Database\SQLDatabase;
use Ipeweb\RecapSheets\Model\ProjectInvite;
use Ipeweb\RecapSheets\Model\QueryGet;
use Ipeweb\RecapSheets\Model\UserProjectsData;

class EmailInviteController
{
    public static function searchUser()
    {
        $query = QueryGet::getQueryItems(["search" => true, "project_id" => true]);

        $sqlDatabase = new SQLDatabase;
        $sqlDatabase->setQuery(sprintf('SELECT u.name, u.email, u.username, u.id, u.picture_path, COALESCE(pu.user_permissions, \'none\') AS user_permissions FROM users u LEFT JOIN project_users pu ON u.id = pu.user_id AND pu.project_id = %s WHERE u.name ILIKE \'%%%s%%\' OR u.email ILIKE \'%%%s%%\' OR u.username ILIKE \'%%%s%%\' LIMIT 5;', $query['project_id'], $query['search'], $query['search'], $query['search']));

        try {
            $result = $sqlDatabase->execute();

            http_response_code(200);
            return $result;
        } catch (\Throwable $throwable) {
            http_response_code(500);
            throw new \Exception("Something went wrong on getting users: " . $throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }

    public static function setUserPermission()
    {
        $query = QueryGet::getQueryItems(["user_id" => true, "project_id" => true, "user_permissions"]);
        $query['permission'] = $query['permission'] ?? "guest";

        $insertData = ['user_id' => $query['user_id'], 'project_id' => $query['project_id'], 'user_permissions' => $query['permission']];

        try {
            $userProjectsData = new UserProjectsData();
            $resultGet = $userProjectsData->getSearch(['user_id' => $query['user_id'], 'project_id' => $query['project_id']], 0, 1, null, true);

            if ($resultGet === []) {
                $result = $userProjectsData->insert($insertData);


                if ($result !== []) {
                    $projectInvite = new ProjectInvite();
                    $projectInvite->sendInvite($query['user_id'], ["name" => Request::$decodedToken['name'], "email" => Request::$decodedToken['email']], $query['project_id']);

                    http_response_code(200);
                    return $result;
                } else {
                    http_response_code(500);
                    throw new \Exception("Could not invite user");
                }
            } else {
                http_response_code(403);
                throw new \Exception("This user is already invited");
            }
        } catch (\Throwable $throwable) {
            http_response_code(500);
            throw new \Exception("Something went wrong on inviting user:" . $throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }
}
