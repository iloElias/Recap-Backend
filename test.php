<?php

use Ipeweb\RecapSheets\Bootstrap\Environments;
use Ipeweb\RecapSheets\Bootstrap\Helper;
use Ipeweb\RecapSheets\Services\Mail;

require_once('./vendor/autoload.php');

Environments::getEnvironments();

// function printSum($x, $y, $z)
// {
//     echo ($x . $y . $z);
// }

// $arr = [
//     "y" => 'Y',
//     "z" => 'Z',
//     "x" => 'X',
// ];

// printSum(...$arr);


$mail = new Mail(Helper::env('GOOGLE_APP_EMAIL'));
$response = $mail->sendEmail('murilo7546@gmail.com', 'Email teste', 'Teste');

if ($response) {
    echo "Funcionou " . $response;
} else {
    echo "Não funcionou " . $response;
}
