<?php

namespace Ipeweb\RecapSheets\Controller;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Database\PDOConnection;
use Ipeweb\RecapSheets\Model\UserData;
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

        $sqlDatabase = PDOConnection::getPdoInstance();
        $sqlStatement = $sqlDatabase->prepare("SELECT u.name, u.email, u.username, u.id, u.picture_path, COALESCE(pu.user_permissions, 'none') AS user_permissions FROM users u LEFT JOIN project_users pu ON u.id = pu.user_id AND pu.project_id = {$project_id} WHERE u.name ILIKE '%{$search}%' OR u.email ILIKE '%{$search}%' OR u.username ILIKE '%{$search}%';");

        try {
            $sqlStatement->execute();

            $result = $sqlStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $key => $value) {
                if ($value["email"] === Request::$decodedToken["email"]) {
                    unset($result[$key]);
                }
            }

            http_response_code(200);
            exit(JWT::encode($result));
        } catch (\Throwable $e) {
            http_response_code(500);
            exit(json_encode(["message" => "Something went wrong on getting users:" . $e->getMessage()]));
        }
    }
}