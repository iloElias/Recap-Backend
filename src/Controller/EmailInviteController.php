<?php

namespace Ipeweb\RecapSheets\Controller;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Database\PDOConnection;
use Ipeweb\RecapSheets\Database\SQLDatabase;
use Ipeweb\RecapSheets\Model\ProjectInvite;
use Ipeweb\RecapSheets\Model\QueryGet;
use Ipeweb\RecapSheets\Model\UserData;
use Ipeweb\RecapSheets\Model\UserProjectsData;
use Ipeweb\RecapSheets\Services\JWT;

class EmailInviteController
{
    public static function searchUser()
    {
        $query = QueryGet::getQueryItems(["search" => true, "project_id" => true]);

        $sqlDatabase = new SQLDatabase;
        $sqlDatabase->setQuery("SELECT u.name, u.email, u.username, u.id, u.picture_path, COALESCE(pu.user_permissions, 'none') AS user_permissions FROM users u LEFT JOIN project_users pu ON u.id = pu.user_id AND pu.project_id = {$query['project_id']} WHERE u.name ILIKE '%{$query['search']}%' OR u.email ILIKE '%{$query['search']}%' OR u.username ILIKE '%{$query['search']}%' LIMIT 5;");

        try {
            $result = $sqlDatabase->execute();

            http_response_code(200);
            return $result;
        } catch (\Throwable $e) {
            http_response_code(500);
            exit(json_encode(["message" => "Something went wrong on getting users: " . $e->getMessage()]));
        }
    }

    public static function setUserPermission()
    {
        $query = QueryGet::getQueryItems(["user_id" => true, "project_id" => true, "user_permissions"]);
        $query['permission'] = $query['permission'] ?? "guest";

        $insertData = ['user_id' => $query['user_id'], 'project_id' => $query['project_id'], 'user_permissions' => $query['permission']];

        try {
            $projectUserService = new UserProjectsData();
            $resultGet = $projectUserService->getSearch(['user_id' => $query['user_id'], 'project_id' => $query['project_id']], 0, 1, null, true);

            if (empty($resultGet)) {
                $result = $projectUserService->insert($insertData);


                if ($result) {
                    $emailService = new ProjectInvite();
                    $emailService->sendInvite($query['user_id'], ["name" => Request::$decodedToken['name'], "email" => Request::$decodedToken['email']], $query['project_id']);

                    http_response_code(200);
                    return $result;
                } else {
                    http_response_code(500);
                    exit(["message" => "Could not invite user"]);
                }
            } else {
                http_response_code(403);
                exit(json_encode(["message" => "This user is already invited"]));
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            exit(json_encode(["message" => "Something went wrong on inviting user:" . $e->getMessage()]));
        }
    }
}
