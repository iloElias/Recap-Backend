<?php

namespace Ipeweb\RecapSheets\Model;

use Ipeweb\RecapSheets\Bootstrap\Helper;
use Ipeweb\RecapSheets\Internationalization\LanguageHandler;
use Ipeweb\RecapSheets\Model\Strategy\InviteStrategy;
use Ipeweb\RecapSheets\Services\Mail;

class ProjectInvite implements InviteStrategy
{
    public function sendInvite($to, $subject)
    {
        if (!isset($subject['project_id']) || !isset($subject['permission'])) {
            throw new \InvalidArgumentException("Some of the required data to send a project invite was not provided");
        }

        $userService = new UserData();
        $invitedUserData = $userService->getSearch(["email" => $to], 0, 1, strict: true);

        if ($invitedUserData) {
            $language = $invitedUserData['preferred_lang'];

            $preparedEmailHTML = file_get_contents('EmailTemplate.html');

            foreach (LanguageHandler::MAIL_MESSAGES[strtolower($language)] as $key => $value) {
                $preparedEmailHTML = str_replace($key, $value, $preparedEmailHTML);
            }

            $mail = new Mail(Helper::env("GOOGLE_APP_EMAIL"));
            $result = $mail->sendEmail($to, LanguageHandler::MAIL_MESSAGES[strtolower($language)]["project_invite_title"], $preparedEmailHTML);
            return $result;
        }
    }
}
