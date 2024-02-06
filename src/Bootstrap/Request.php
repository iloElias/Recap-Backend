<?php

namespace Ipeweb\IpeSheets\Bootstrap;

use Ipeweb\IpeSheets\Internationalization\Translator;
use Ipeweb\IpeSheets\Services\Utils;

class Request
{
    private const PERMITTED_ACCESS_ORIGINS = [
        'http://localhost:3000',
        'http://localhost:3000/',
        'https://ipeweb.recap.com:3000',
        'https://ipeweb.recap.com:3000/',
        'https://ipeweb-recap.vercel.app',
        'https://ipeweb-recap.vercel.app/',
    ];

    public static function init()
    {
        self::cors();

        $lang = isset($_GET["lang"]) ? $_GET["lang"] : 'en';
        $about = isset($_GET["about"]) ? $_GET["about"] : "noSelected";

        Request::validateRequest($lang);

        $body = Request::getRequestBody();

        $return = match ($_SERVER['REQUEST_METHOD']) {
            'GET' => function (?string $about, $body, string $lang) {
                if (isset($_GET["message"])) {
                    if ($_GET["message"] == 'all') {
                        echo json_encode(
                            Translator::getAllFrom($lang)
                        );
                        return;
                    }

                    $message = "";
                    $params = null;

                    if (isset($_GET["params"])) {
                        $params = explode('%', $_GET["params"]);
                    }

                    $message = Translator::translate($lang, $_GET["message"], $params);

                    echo json_encode(
                        $message
                    );
                    return;
                }

                $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $perPage = isset($_GET['per_page']) ? intval($_GET['per_page']) : 25;
                $filter = isset($_GET['filter']) ? $_GET['filter'] : [];
                $field = isset($_GET['field']) ? $_GET['field'] : [];
                $sort = isset($_GET['sort']) ? $_GET['sort'] : [];

                try {
                    $databaseClass = new ('Ipeweb\IpeSheets\Model\\' . ucfirst($about) . "Data");

                    if (!empty($field)) {
                        $fieldName = explode(':', $field)[0];
                        $fieldValue = explode(':', $field)[1];

                        $result = $databaseClass->get($fieldName, $fieldValue);
                        echo json_encode($result);
                        return;
                    }

                    if (!empty($filter)) {
                        if (!empty($sort)) {
                            $sortArray = [
                                'field' => explode(":", $sort)[0],
                                'direction' => explode(":", $sort)[1]
                            ];
                            $result = $databaseClass->getSearch(($page - 1) * $perPage, $perPage, [explode(":", $filter)[0] => explode(":", $filter)[1]], $sortArray);
                        } else {
                            $result = $databaseClass->getSearch(($page - 1) * $perPage, $perPage, [explode(":", $filter)[0] => explode(":", $filter)[1]]);
                        }
                        echo json_encode($result);
                        return;
                    } else {
                        if (!empty($sort)) {
                            $sortArray = [
                                'field' => explode(":", $sort)[0],
                                'direction' => explode(":", $sort)[1]
                            ];
                            $result = $databaseClass->getAll(($page - 1) * $perPage, $perPage, $sortArray);
                        } else {
                            $result = $databaseClass->getAll(($page - 1) * $perPage, $perPage);
                        }
                        echo json_encode($result);
                        return;
                    }
                } catch (\Throwable $e) {
                    echo json_encode(
                        [
                            "message" => Translator::translate($lang, 'not_available_service', $about, true),
                            "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine(),
                        ]
                    );
                }
            },
            'POST' => function (string $about, $body, string $lang) {
                try {
                    $mapClass = new ('Ipeweb\IpeSheets\Model\\' . ucfirst($about));

                    foreach ($body as $key => $value) {
                        $mapClass->$key = $value;
                    }

                    if ($mapClass->validate()) {
                        $dataClass = new ('Ipeweb\IpeSheets\Model\\' . ucfirst($about) . "Data");

                        $response = $dataClass->getSearch($body, strict: true);
                        if ($response) {
                            echo json_encode([
                                "message" => "There is already a {$about} registered with the given data",
                                "id" => $response[0]['id'],
                            ]);
                            return;
                        }

                        $dataClass->insert($body);

                        echo json_encode([
                            "message" => Translator::translate($lang, 'item_bd_new_inserted', $about, true)
                        ]);
                        return;
                    }
                    echo json_encode([
                        "message" => Translator::translate($lang, 'invalid_post_body', $about, true)
                    ]);
                } catch (\Throwable $e) {
                    echo json_encode(
                        [
                            "message" => Translator::translate($lang, 'not_available_service', $about, true),
                            "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine(),
                        ]
                    );
                }
            },
            'PUT' => function (string $about, $body, string $lang) {
                try {
                    $mapClass = new ('Ipeweb\IpeSheets\Model\\' . ucfirst($about));

                    foreach ($body as $key => $value) {
                        $mapClass->$key = $value;
                    }

                    if ($mapClass->validate()) {
                        $dataClass = new ('Ipeweb\IpeSheets\Model\\' . ucfirst($about) . "Data");
                        $dataClass->update($mapClass->id, $body);
                    }

                    echo json_encode([
                        "message" => ucfirst($about) . " has been updated"
                    ]);
                } catch (\Throwable $e) {
                    echo json_encode(
                        [
                            "message" => Translator::translate($lang, 'not_available_service', $about, true),
                            "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine(),
                        ]
                    );
                }
            },
            'DELETE' => function (string $about, $body, string $lang) {
                try {
                    $mapClass = new ('Ipeweb\IpeSheets\Model\\' . ucfirst($about))(id: $body["id"]);

                    foreach ($body as $key => $value) {
                        if ($key != "id") {
                            $mapClass->$key = $value;
                        }
                    }

                    if ($mapClass->validate()) {
                        $dataClass = new ('Ipeweb\IpeSheets\Model\\' . ucfirst($about) . "Data");
                        $dataClass->inactive($mapClass->id, $body);
                    }

                    echo json_encode([
                        "message" => ucfirst($about) . " has been inactivated"
                    ]);
                } catch (\Throwable $e) {
                    echo json_encode(
                        [
                            "message" => Translator::translate($lang, 'not_available_service', $about, true),
                            "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine(),
                        ]
                    );
                }
            }
        };

        try {
            $return($about, $body, $lang);
            return;
        } catch (\Throwable $e) {
            echo json_encode(
                [
                    "message" => Translator::translate($lang, 'not_detected_problem', returnOnSupported: true),
                    "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine(),
                ]
            );
        }
    }

    public static function getRequestBody()
    {
        $file = json_decode(file_get_contents('php://input'), true);
        if (empty($file) || !is_array($file)) {
            return [];
        }

        $data = [];

        foreach ($file[0] as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    public static function getRequestHeader()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        return $headers;
    }

    public static function authenticateToken(string $token): bool
    {
        return true;
    }

    public static function cors()
    {
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Methods: GET, POST, PUT,DELETE, OPTIONS");
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                http_response_code(200);
                echo json_encode(array("message" => "OK!"));
                exit(0);
            }
        }
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            if (Utils::arrayFind(self::PERMITTED_ACCESS_ORIGINS, $_SERVER['HTTP_ORIGIN'])) {
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Max-Age: 86400');
            }
        }
        header("Access-Control-Allow-Origin: *");
    }

    public static function validateRequest(string $lang = 'en')
    {
        // if (!key_exists("url", $_REQUEST)) {
        //     echo json_encode(
        //         [
        //             "message" => Translator::translate($lang, 'invalid_request', returnOnSupported: true)
        //         ]
        //     );
        //     exit;
        // }



        // if (!key_exists("Token", Request::getRequestHeader())) {
        // echo json_encode(
        // [
        // "message" => Responses::REQUEST_RESPONSES["noProvidedToken"],
        // ]
        // );
        // exit;
        // }
        // if (!Request::authenticateToken(Request::getRequestHeader()["Token"])) {
        // echo json_encode(
        // [
        // "message" => Responses::REQUEST_RESPONSES["notValidToken"],
        // ]
        // );
        // exit;
        // }
    }
}
