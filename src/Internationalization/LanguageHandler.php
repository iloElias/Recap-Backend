<?php

namespace Ipeweb\RecapSheets\Internationalization;

class LanguageHandler
{
    public const MESSAGES = [
        "en" => [
            "loaded" => true,
            "format_test" => "This :str is :str a :str test",

            "invalid_request" => "Important request information not received",
            "invalid_post_body" => "Important data was not included in the request body, or unnecessary data was sent",
            "not_detected_problem" => "Something in the request went wrong",

            "item_bd_new_inserted" => "New :str has been created",

            "item_bd_updated" => "A register from :str was updated",
            "item_bd_inactivated" => "A register from :str was inactivated",

            "login_static_message" => "Sign in with your Google account to log in",
            "login_button_message" => "Sign with Google",
            "go_back_home" => "Redirect to home page",

            "languages_button_title" => "Languages",
            "about_us_button_title" => "About us",
            "account_logout_button_title" => "Exit the system",
            "styles_button_title" => "Styles",
            "export_project_button_title" => "Export",
            "share_project_button_title" => "Share",

            "export_file_as_png" => "Export as .png",
            "export_file_as_pdf" => "Export as .pdf",

            "cards_page_title" => "Cards",
            "card_item_new_card" => "new card",

            "form_title_new_card" => "New card",
            "label_card_name" => "Card name",
            "label_card_synopsis" => "Synopsis",
            "form_button_new_card" => "Create new card",

            "tooltip_create_card_label" => "Create a new card",
            "tooltip_create_card_synopsis_label" => "Creating a new card, it will be available in the card list on the side once created",

            "item_new_created" => "New :str has been created",
            "item_updated" => "The :str was successfully updated",
            "item_creation_error" => "An error ocurred creating a :str",
            "item_update_error" => "An error ocurred while updating :str",
            "card" => "card",
            "project" => "project",

            "legend_hide_code_editor" => "Hide code editor",
            "legend_reload_view" => "Reload view",
            "legend_save_current_state" => "Save text current state",
            "legend_toggle_mobile_desktop" => "Toggle between mobile and desktop",

            "not_invited_to" => "You were not invited to this project",
            "not_allowed_to_edit" => "You are not allowed to edit this project",
            "not_found_page" => "Page not found",
            "not_found_project" => "Unable to find a project with the given ID",

            "reauthenticate_logout_message" => "Authentication time has expired, please log in again",
            "reauthenticate_token_message" => "There was a change on your access token, please log in again",

            "loading_your_cards" => "We are loading your cards, wait a few seconds",
            "loading_your_project" => "We are loading the project data, wait a few seconds",
            "problem_when_loading" => "There was a problem while loading some data",

            "invalid_synopsis_length" => "Minimum size is :str",
            "required_input_message" => "Required",
        ],
        "pt-br" => [
            "loaded" => true,
            "format_test" => "Isso :str é :str um :str teste",

            "invalid_request" => "Dados importantes para a requisição não foram recebidos",
            "invalid_post_body" => "Dados importantes não foram incluídos no corpo da requisição, ou dados desnecessários foram enviados",
            "not_detected_problem" => "Parece que algo deu errado",

            "item_bd_new_inserted" => "Um novo :str foi criado",
            "item_bd_updated" => "Um registro de :str foi atualizado",
            "item_bd_inactivated" => "Um registro de :str foi desativado",

            "login_static_message" => "Faça login com sua conta Google para entrar",
            "login_button_message" => "Entrar com o Google",
            "go_back_home" => "Voltar para a pagina inicial",

            "languages_button_title" => "Idiomas",
            "about_us_button_title" => "Sobre nós",
            "account_logout_button_title" => "Sair do Sistema",
            "styles_button_title" => "Estilos",
            "export_project_button_title" => "Exportar",
            "share_project_button_title" => "Compartilhar",

            "export_file_as_png" => "Exportar como .png",
            "export_file_as_pdf" => "Exportar como .pdf",

            "cards_page_title" => "Cartões",
            "card_item_new_card" => "criar cartão",

            "form_title_new_card" => "Novo cartão",
            "label_card_name" => "Nome do cartão",
            "label_card_synopsis" => "Sinopse",
            "form_button_new_card" => "Criar novo cartão",

            "tooltip_create_card_label" => "Crie um novo cartão",
            "tooltip_create_card_synopsis_label" => "Crie um novo cartão, ele estará disponível na lista de cartões ao lado depois de criado",

            "item_new_created" => "Um novo :str foi criado",
            "item_updated" => "O :str foi salvo com sucesso",
            "item_creation_error" => "Um erro ocorreu durante a criação de um :str",
            "item_update_error" => "Um erro ocorreu durante o salvamento do(a) :str",
            "card" => "cartão",
            "project" => "projeto",

            "legend_hide_code_editor" => "Esconder o editor de texto",
            "legend_reload_view" => "Recarregar visualizador",
            "legend_save_current_state" => "Salvar estado atual do texto",
            "legend_toggle_mobile_desktop" => "Alterar entre visualização mobile e desktop",

            "not_invited_to" => "Você não foi convidado para este projeto",
            "not_allowed_to_edit" => "Você não tem permissão para editar este projeto",
            "not_found_page" => "Pagina não encontrada",
            "not_found_project" => "Não foi possível encontrar um projeto com o ID fornecido",

            "reauthenticate_logout_message" => "O tempo de autenticação expirou, faça login novamente",
            "reauthenticate_token_message" => "Houve uma alteração no token de acesso, faça login novamente",

            "loading_your_cards" => "Estamos carregando seus cartões, aguarde alguns segundos",
            "loading_your_project" => "Estamos carregando os dados do projeto, aguarde alguns segundos",
            "problem_when_loading" => "Ocorreu um problema enquanto carregávamos alguns dados",

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
