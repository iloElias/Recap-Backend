<?php

namespace Ipeweb\IpeSheets\Internationalization;

class LanguageHandler
{
    public const MESSAGES = [
        "en" => [
            "loaded" => true,
            "format_test" => "This :str is :str a :str test",
            "language_test" => "This is a language test",

            "any_service_informed" => "Any service was selected. To fix, add '?about=' and the name of required service in the url query",

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

            "cards_page_title" => "Cards",
            "card_item_new_card" => "new card",

            "form_title_new_card" => "New card",
            "label_card_name" => "Card name",
            "label_card_synopsis" => "Synopsis",
            "form_button_new_card" => "Create new card",

            "item_new_created" => "New :str has been created",
            "item_creation_error" => "An error ocurred creating a :str",
            "card" => "card",
            "project" => "project",

            "invalid_synopsis_length" => "Minimum size is :str",
            "required_input_message" => "Required",
        ],
        "pt-br" => [
            "loaded" => true,
            "format_test" => "Isso :str é :str um :str teste",
            "language_test" => "Este é um teste de linguagem",

            "any_service_informed" => "Nenhum serviço foi selecionado. Adicione '?about=' e o nome do serviço desejado na url para prosseguir",

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

            "languages_button_title" => "Idiomas",
            "about_us_button_title" => "Sobre nós",
            "account_logout_button_title" => "Sair do Sistema",

            "cards_page_title" => "Cartões",
            "card_item_new_card" => "criar cartão",

            "form_title_new_card" => "Novo cartão",
            "label_card_name" => "Nome do cartão",
            "label_card_synopsis" => "Sinopse",
            "form_button_new_card" => "Criar novo cartão",

            "item_new_created" => "Um novo :str foi criado",
            "item_creation_error" => "Um erro ocorreu durante a criação de um :str",
            "card" => "cartão",
            "project" => "projeto",

            "invalid_synopsis_length" => "O tamanho mínimo é :str",
            "required_input_message" => "Obrigatório",
        ],
    ];

    public static function getAll(string $lang): array
    {
        $lang = strtolower($lang);

        if (!isset(self::MESSAGES[$lang])) {
            throw new \InvalidArgumentException("Language handler do not offer support to the provided language: " . $lang);
        }
        return self::MESSAGES[$lang];
    }

    public static function getMessage(string $lang, string $message, ?bool $returnOnSupported = false): ?string
    {
        $lang = strtolower($lang);

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
