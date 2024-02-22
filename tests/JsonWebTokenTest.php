<?php

use Ipeweb\RecapSheets\Exceptions\InvalidTokenSignature;
use Ipeweb\RecapSheets\Internationalization\LanguageHandler;
use Ipeweb\RecapSheets\Services\JWT;
use PHPUnit\Framework\TestCase;


class JsonWebTokenTest extends TestCase
{
    private readonly array $data;

    public function setUp(): void
    {
        $this->data = [
            'name' => 'John Doe',
            'email' => 'john.doe@email.com',
            'google_id' => '12453211253215',
            'picture_path' => 'https://google.picture/?user_id=12453211253215',
        ];
    }

    public function testAssertEncode(): void
    {
        $encoded = JWT::encode($this->data, 'secret_test');

        $this->assertEquals('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSm9obiBEb2UiLCJlbWFpbCI6ImpvaG4uZG9lQGVtYWlsLmNvbSIsImdvb2dsZV9pZCI6IjEyNDUzMjExMjUzMjE1IiwicGljdHVyZV9wYXRoIjoiaHR0cHM6XC9cL2dvb2dsZS5waWN0dXJlXC8_dXNlcl9pZD0xMjQ1MzIxMTI1MzIxNSJ9.dPlXSyNWEThD_gS6ygTsOffxGsaPkpqFOwVmwQRwQfQ', $encoded);
    }

    public function testAssertDecode(): void
    {
        $decoded = JWT::decode('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSm9obiBEb2UiLCJlbWFpbCI6ImpvaG4uZG9lQGVtYWlsLmNvbSIsImdvb2dsZV9pZCI6IjEyNDUzMjExMjUzMjE1IiwicGljdHVyZV9wYXRoIjoiaHR0cHM6XC9cL2dvb2dsZS5waWN0dXJlXC8_dXNlcl9pZD0xMjQ1MzIxMTI1MzIxNSJ9.dPlXSyNWEThD_gS6ygTsOffxGsaPkpqFOwVmwQRwQfQ', 'secret_test');

        $this->assertEquals($this->data, $decoded);
    }

    public function testJWTException()
    {
        $this->expectException(InvalidTokenSignature::class);
        $encoded = JWT::encode($this->data, 'secret_test');

        JWT::decode($encoded, 'wrong_secret');
    }

    public function testJWTExceptionBadToken()
    {
        $this->expectException(InvalidTokenSignature::class);
        $unauthenticatedToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSm9obiBEb2UiLCJlbWFpbCI6ImpvaG4uZG9lQGVtYWlsLmNvbSIsImdvb2dsZV9pZCI6IjEyMzE1NDUxMTIzMSIsInBpY3R1cmVfcGF0aCI6Imh0dHBzOi8vZ29vZ2xlLnBpY3R1cmUvP3VzZXJfaWQ9MTI0NTMyMTEyNTMyMTUifQ.AB4nazyo3yGdaDhGHWSO9ZYRPmIFcj5A_JzYyPVzJGQ';

        JWT::decode($unauthenticatedToken, 'secret_test');
    }

    public function testJWTAuthenticationByPass()
    {
        $this->expectException(InvalidTokenSignature::class);
        $unauthenticatedToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSm9obiBEb2UiLCJlbWFpbCI6ImpvaG4uZG9lQGVtYWlsLmNvbSIsImdvb2dsZV9pZCI6IjczMjc2MjM2MiIsInBpY3R1cmVfcGF0aCI6Imh0dHBzOi8vZ29vZ2xlLnBpY3R1cmUvP3VzZXJfaWQ9MTI0NTMyMTEyNTMyMTUifQ.dPlXSyNWEThD_gS6ygTsOffxGsaPkpqFOwVmwQRwQfQ';

        JWT::decode($unauthenticatedToken, 'secret_test');
    }
}
