<?php

namespace Ipeweb\RecapSheets\Model;

use Ipeweb\RecapSheets\Bootstrap\Helper;
use Ipeweb\RecapSheets\Internationalization\LanguageHandler;
use Ipeweb\RecapSheets\Model\Strategy\InviteStrategy;
use Ipeweb\RecapSheets\Services\Mail;

class ProjectInvite implements InviteStrategy
{
    public function sendInvite($toId, $inviterData, $projectID)
    {
        $userData = new UserData();
        $invitedUserData = $userData->getSearch(["id" => $toId], 0, 1, null, true);

        if ($invitedUserData !== []) {
            $language = $invitedUserData[0]['preferred_lang'];
            $email = $invitedUserData[0]['email'];

            $preparedEmailHTML = file_get_contents('./src/EmailTemplate.html');

            foreach (LanguageHandler::MAIL_MESSAGES[strtolower($language)] as $key => $value) {
                if ($key === "INVITED_BY_SOMEONE_MESSAGE") {
                    $value = str_replace(':str1', $inviterData['name'], $value);
                    $value = str_replace(':str2', $inviterData['email'], $value);

                    $preparedEmailHTML = str_replace($key, $value, $preparedEmailHTML);
                } else {
                    $preparedEmailHTML = str_replace($key, $value, $preparedEmailHTML);
                }
            }

            $applicationBaseURL = Helper::env("APPLICATION_WEB_BASE_URL");

            $preparedEmailHTML = str_replace('GO_TO_PROJECT_URL', (sprintf('%s/project/%s', $applicationBaseURL, $projectID)), $preparedEmailHTML);
            $preparedEmailHTML = str_replace('APP_LOGINPAGE', ($applicationBaseURL . '/login'), $preparedEmailHTML);

            try {
                $mail = new Mail(Helper::env("GOOGLE_APP_EMAIL"));
                $result = $mail->sendEmail($email, LanguageHandler::MAIL_MESSAGES[strtolower($language)]["project_invite_title"], $preparedEmailHTML);

                if ($result) {
                    http_response_code(200);
                    return ["message" => "Email sent successfully", "success" => true];
                }
            } catch (\Throwable $e) {
                http_response_code(500);
                throw new \Exception("Something went wrong on sending invite email: " . $e->getMessage(), $e->getCode(), $e);
            }
        }
    }
}
