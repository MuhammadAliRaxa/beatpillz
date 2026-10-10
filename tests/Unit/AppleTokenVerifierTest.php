<?php

namespace Tests\Unit;

use App\Services\AppleTokenVerifier;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AppleTokenVerifierTest extends TestCase
{
    public function test_rejects_malformed_token()
    {
        $this->expectException(\Exception::class);
        AppleTokenVerifier::verify('not.a.valid.jwt');
    }

    public function test_rejects_token_with_invalid_issuer()
    {
        // Generate temporary RSA key pair
        $res = openssl_pkey_new([
            'digest_alg' => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);
        openssl_pkey_export($res, $privateKey);
        $details = openssl_pkey_get_details($res);

        // Convert n and e to base64url
        $modulus = rtrim(strtr(base64_encode($details['rsa']['n']), '+/', '-_'), '=');
        $exponent = rtrim(strtr(base64_encode($details['rsa']['e']), '+/', '-_'), '=');

        $kid = 'test_key_1';
        $fakeJwk = [
            'keys' => [
                [
                    'kty' => 'RSA',
                    'kid' => $kid,
                    'use' => 'sig',
                    'alg' => 'RS256',
                    'n'   => $modulus,
                    'e'   => $exponent,
                ]
            ]
        ];

        Cache::put('apple_auth_public_keys', $fakeJwk, 60);

        // JWT with incorrect issuer
        $payload = [
            'iss' => 'https://evil-issuer.com',
            'aud' => 'com.beatpillz.app',
            'exp' => time() + 3600,
            'sub' => '001234.abcdef',
            'email' => 'user@example.com'
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256', $kid);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid Apple token issuer');
        AppleTokenVerifier::verify($jwt);
    }

    public function test_successfully_verifies_valid_apple_token()
    {
        // Generate temporary RSA key pair
        $res = openssl_pkey_new([
            'digest_alg' => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);
        openssl_pkey_export($res, $privateKey);
        $details = openssl_pkey_get_details($res);

        $modulus = rtrim(strtr(base64_encode($details['rsa']['n']), '+/', '-_'), '=');
        $exponent = rtrim(strtr(base64_encode($details['rsa']['e']), '+/', '-_'), '=');

        $kid = 'test_key_apple';
        $fakeJwk = [
            'keys' => [
                [
                    'kty' => 'RSA',
                    'kid' => $kid,
                    'use' => 'sig',
                    'alg' => 'RS256',
                    'n'   => $modulus,
                    'e'   => $exponent,
                ]
            ]
        ];

        Cache::put('apple_auth_public_keys', $fakeJwk, 60);

        $payload = [
            'iss' => 'https://appleid.apple.com',
            'aud' => 'com.beatpillz.app',
            'exp' => time() + 3600,
            'sub' => '001234.verified_apple_sub',
            'email' => 'user@privaterelay.appleid.com'
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256', $kid);

        $claims = AppleTokenVerifier::verify($jwt);
        $this->assertEquals('001234.verified_apple_sub', $claims->sub);
        $this->assertEquals('user@privaterelay.appleid.com', $claims->email);
    }
}
