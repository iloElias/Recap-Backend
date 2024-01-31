<?php

namespace Ipeweb\IpeSheets\Services;

class Email
{
    public static function validate(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
