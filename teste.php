<?php

use Ipeweb\IpeSheets\Services\JWT;

require_once('./vendor/autoload.php');


$endcodedData = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.W3siaWQiOjk4LCJ1c2VybmFtZSI6Im11cmlsbzc0NTYiLCJwaWN0dXJlX3BhdGgiOiJodHRwczpcL1wvbGgzLmdvb2dsZXVzZXJjb250ZW50LmNvbVwvYVwvQUNnOG9jSW4xRXFPUXNsRkNSOG9QcEFTUURfZEFMYS1xcWRnUVZXbHVwUm42OFJmbnNVPXM5Ni1jIiwicHJlZmVycmVkX2xhbmciOiJwdC1CUiIsImNyZWF0ZWRfaW4iOiIyMDI0LTAyLTA5IiwidmlzaWJsZSI6dHJ1ZSwiaXNfYWN0aXZlIjp0cnVlLCJlbWFpbCI6Im11cmlsbzc0NTZAZ21haWwuY29tIiwibmFtZSI6Ik11cmlsb0VTRiIsImdvb2dsZV9pZCI6IjEwMjIxNjQwMDA5Mzc0ODQ1MDE1OSIsImxvZ2dlZF9pbiI6IjIwMjQtMDItMTQifV0.sGy-fZxUdLCxY8Jity9tJgTOgQX6pAHZYjfzvvH123Q';

$test = JWT::encode([
    json_decode('{
    "id": 98,
    "username": "murilo7456",
    "picture_path": "https://lh3.googleusercontent.com/a/ACg8ocIn1EqOQslFCR8oPpASQD_dALa-qqdgQVWlupRn68RfnsU=s96-c",
    "preferred_lang": "pt-BR",
    "created_in": "2024-02-09",
    "visible": true,
    "is_active": true,
    "email": "murilo7456@gmail.com",
    "name": "MuriloESF",
    "google_id": "102216400093748450159",
    "logged_in": "2024-02-14"
  }')
]);

// var_dump(JWT::decode($endcodedData));
print($test);
