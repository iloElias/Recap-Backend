<?php

namespace Ipeweb\IpeSheets\Internationalization;

class LanguageHandler
{
    public const MESSAGES = [
        "en" => [
            "format_test" => "This :str is :str a :str test",
            "language_test" => "This is a language test",

            "hello_user" => "Hello, :str!",
            "welcome" => "Welcome to our application.",
            "we_were_waiting" => "We were waiting for you",
            "not_available_service" => "The service :str is not available or could not be found",

            "invalid_request" => "Important request information not received",
            "invalid_post_body" => "Important data was not included in the request body, or unnecessary data was sent",
            "not_detected_problem" => "Something in the request went wrong",

            "item_bd_new_inserted" => "New :str has been created",
            "item_bd_updated" => "A register from :str was updated",
            "item_bd_inactivated" => "A register from :str was inactivated",

            "login_static_message" => "Sign in with your Google account to log in",
            "login_button_message" => "Sign with Google",

            "languages_button_title" => "Languages",
            "about_us_button_title" => "About us",
            "account_logout_button_title" => "Exit the system",
        ],
        "pt-BR" => [
            "format_test" => "Isso :str é :str um :str teste",
            "language_test" => "Este é um teste de linguagem",

            "hello_user" => "Olá, :str!",
            "welcome" => "Seja bem-vindo ao nosso aplicativo.",
            "we_were_waiting" => "Nós estávamos te esperando",
            "not_available_service" => "O serviço :str não está disponível ou não pode ser encontrado",

            "invalid_request" => "Dados importantes para a requisição não foram recebidos",
            "invalid_post_body" => "Dados importantes não foram incluídos no corpo da requisição, ou dados desnecessários foram enviados",
            "not_detected_problem" => "Parece que algo deu errado",

            "item_bd_new_inserted" => "Um novo :str foi criado",
            "item_bd_updated" => "Um registro de :str foi atualizado",
            "item_bd_inactivated" => "Um registro de :str foi desativado",

            "login_static_message" => "Faça login com sua conta Google para entrar",
            "login_button_message" => "Entrar com o Google",

            "languages_button_title" => "Linguas",
            "about_us_button_title" => "Sobre nós",
            "account_logout_button_title" => "Sair do Sistema",
        ],
    ];

    public static function getAll(string $lang): array
    {
        if (!isset(self::MESSAGES[$lang])) {
            throw new \InvalidArgumentException("Language handler do not offer support to the provided language: " . $lang);
        }
        return self::MESSAGES[$lang];
    }

    public static function getMessage(string $lang, string $message, ?bool $returnOnSupported = false): ?string
    {
        if (!isset(self::MESSAGES[$lang])) {
            if ($returnOnSupported) {
                return self::getMessage('en', $message, false);
            }
            throw new \InvalidArgumentException("Language handler do not offer support to the provided language: " . $lang);
        }
        if (!isset(self::MESSAGES[$lang][$message])) {
            throw new \InvalidArgumentException("The message '" . $message . "' was not covered on '" . $lang . "' language support");
        }

        return self::MESSAGES[$lang][$message];
    }
}
