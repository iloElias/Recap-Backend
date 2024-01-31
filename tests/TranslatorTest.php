<?php

use Ipeweb\IpeSheets\Internationalization\Translator;
use PHPUnit\Framework\TestCase;

class TranslatorTest extends TestCase
{
    public function testTranslateWithNoFormatting(): void
    {
        $resultEn = Translator::translate('en', 'hello_user');
        $resultPt = Translator::translate('pt-BR', 'hello_user');

        $this->assertEquals('Hello, :str!', $resultEn);
        $this->assertEquals('Olá, :str!', $resultPt);
    }

    public function testTranslateWithFormatting(): void
    {
        $resultEn = Translator::translate('en', 'hello_user', 'John');
        $resultPt = Translator::translate('pt-BR', 'hello_user', 'João');

        $this->assertEquals('Hello, John!', $resultEn);
        $this->assertEquals('Olá, João!', $resultPt);
    }

    public function testTranslateWithArrayFormatting(): void
    {
        $resultEn = Translator::translate('en', 'format_test', ['this', 'is', 'a']);
        $resultPt = Translator::translate('pt-BR', 'format_test', ['isso', 'é', 'um']);

        $this->assertEquals('This this is is a a test', $resultEn);
        $this->assertEquals('Isso isso é é um um teste', $resultPt);
    }

    public function testTranslateWithInsufficientArguments(): void
    {
        $resultEn = Translator::translate('en', 'format_test', ['this', 'is']);
        $resultPt = Translator::translate('pt-BR', 'format_test', ['isso', 'é']);

        $this->assertEquals('This this is is a :str test', $resultEn);
        $this->assertEquals('Isso isso é é um :str teste', $resultPt);
    }

    public function testTranslateUnsupportedLanguage(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Translator::translate('fr', 'hello_user');
    }
}
