<?php

require_once('../vendor/autoload.php');

use Ipeweb\IpeSheets\Bootstrap\Environments;
use Ipeweb\IpeSheets\Bootstrap\Request;

error_reporting(0);

$allowedOrigins = [
    'http://localhost:3000',
    'https://ipeweb.recap.com:3000',
    'https://ipeweb-recap.vercel.app',
];

// Verifica se a origem da solicitação está na lista de domínios permitidos
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    // Define os cabeçalhos CORS permitidos
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
    header('Access-Control-Allow-Credentials: true');
}

// Se a solicitação for do tipo OPTIONS, retorna apenas os cabeçalhos CORS sem corpo de resposta
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

Environments::getEnvironments();

Request::init();
