<?php

function printSum($x, $y, $z)
{
    echo ($x . $y . $z);
}

$arr = [
    "y" => 'Y',
    "z" => 'Z',
    "x" => 'X',
];

printSum(...$arr);
