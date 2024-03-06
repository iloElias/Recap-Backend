<?php

namespace Ipeweb\RecapSheets\Controller;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Database\PDOConnection;
use Ipeweb\RecapSheets\Database\SQLDatabase;
use Ipeweb\RecapSheets\Model\UserData;
use Ipeweb\RecapSheets\Model\UserProjectsData;
use Ipeweb\RecapSheets\Services\JWT;
use PDO;

class EmailInviteController
{
    public static function sendEmail()
    {
        if (!isset(Request::$request['body']['from']) and !isset(Request::$request['body']['to']) and !isset(Request::$request['body']['project_info'])) {
            http_response_code(400);
            exit(json_encode([
                'message' => 'Required information not provided'
            ]));
        }
    }

    public static function searchUser()
    {
        if (!isset($_GET["search"])) {
            http_response_code(400);
            exit(json_encode([
                'message' => 'Email to search not provided'
            ]));
        }
        if (!isset($_GET["project_id"])) {
            http_response_code(400);
            exit(json_encode([
                'message' => 'Project id not provided'
            ]));
        }

        $search = $_GET["search"];
        $project_id = $_GET["project_id"];

        $userID = Request::$decodedToken["id"];

        $sqlDatabase = new SQLDatabase;
        $sqlDatabase->setQuery("SELECT u.name, u.email, u.username, u.id, u.picture_path, COALESCE(pu.user_permissions, 'none') AS user_permissions FROM users u LEFT JOIN project_users pu ON u.id = pu.user_id AND pu.project_id = {$project_id} WHERE u.name ILIKE '%{$search}%' OR u.email ILIKE '%{$search}%' OR u.username ILIKE '%{$search}%' LIMIT 5;");

        try {
            $result = $sqlDatabase->execute();

            http_response_code(200);
            exit(JWT::encode($result));
        } catch (\Throwable $e) {
            http_response_code(500);
            exit(json_encode(["message" => "Something went wrong on getting users:" . $e->getMessage()]));
        }
    }

    public static function setUserPermission()
    {
        if (!isset($_GET["user_id"])) {
            http_response_code(400);
            exit(json_encode([
                'message' => 'User id not provided'
            ]));
        }
        if (!isset($_GET["project_id"])) {
            http_response_code(400);
            exit(json_encode([
                'message' => 'Project id not provided'
            ]));
        }

        $user_id = $_GET["user_id"];
        $project_id = $_GET["project_id"];
        $permission = $_GET["user_permissions"] ?? "guest";

        $insertData = ['user_id' => $user_id, 'project_id' => $project_id, 'user_permissions' => $permission];

        try {
            $projectUserService = new UserProjectsData();
            $result = $projectUserService->insert($insertData);

            http_response_code(200);
            exit(json_encode($result));
        } catch (\Throwable $e) {
            http_response_code(500);
            exit(json_encode(["message" => "Something went wrong on inviting user:" . $e->getMessage()]));
        }
    }
}
