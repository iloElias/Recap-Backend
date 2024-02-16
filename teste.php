<?php

use Ipeweb\IpeSheets\Services\JWT;

require_once('./vendor/autoload.php');

$array = [
  'id' => 132,
  'google_id' => '141356152346253',
  'name' => 'Murilo'
];

$encodedJWT = JWT::encode($array);

var_dump(JWT::decode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwiZ29vZ2xlX2lkIjoiMTQxMzU2MTUyMzQ2MjUzIiwibmFtZSI6Ik11cmlsbyJ9.hytTNn3vzYLgcNXD7IvbXEw6drCCjyC71f_KrwQlQ-Y'));

// echo $encodedJWT;