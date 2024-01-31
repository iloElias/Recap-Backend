<?php

use Ipeweb\IpeSheets\Internationalization\LanguageHandler;
use PHPUnit\Framework\TestCase;


class LanguageHandlerTest extends TestCase
{
    public function testGetMessage(): void
    {
        $resultEn = LanguageHandler::getMessage('en', 'hello_user');
        $resultPt = LanguageHandler::getMessage('pt-BR', 'hello_user');

        $this->assertEquals('Hello, :str!', $resultEn);
        $this->assertEquals('Olá, :str!', $resultPt);
    }

    public function testGetMessageUnsupportedLanguage(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        LanguageHandler::getMessage('fr', 'hello_user');
    }

    public function testGetMessageUnsupportedMessage(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        LanguageHandler::getMessage('en', 'nonexistent_message');
    }
}
