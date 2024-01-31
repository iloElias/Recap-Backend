<?php

namespace Ipeweb\IpeSheets\Responses;

class Responses
{
    public const REQUEST_RESPONSES = [
        "notValidToken" => "The sent Token was not found or does not exists.",
        "noProvidedToken" => "Any token provided",
        "finalPageExceeded" => "The last data page as exceeded",
        "noRequestReceived" => "Important request information not received",
        "invalidPostBody" => "Critical data was either not included in the request body, or unnecessary data was sent. Please ensure that all essential information is provided in the request payload, and exclude any irrelevant data",
    ];
}