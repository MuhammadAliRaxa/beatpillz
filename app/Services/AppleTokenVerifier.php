<?php

namespace App\Services;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AppleTokenVerifier
{
    private const APPLE_KEYS_URL = 'https://appleid.apple.com/auth/keys';
    private const APPLE_ISSUER = 'https://appleid.apple.com';
    private const CACHE_KEY = 'apple_auth_public_keys';
    private const CACHE_TTL_SECONDS = 86400; // 24 hours

    /**
     * Verifies an Apple identity token (JWT) and returns its claims.
     *
     * @param string $identityToken
     * @return object Decoded JWT payload claims
     * @throws \Exception If the token is invalid or expired
     */
    public static function verify(string $identityToken): object
    {
        $jwkKeys = self::getApplePublicKeys();

        try {
            // JWT::decode validates signature (RS256) and expiration (exp)
            $decoded = JWT::decode($identityToken, $jwkKeys);
        } catch (\Firebase\JWT\ExpiredException $e) {
            throw new \Exception('The Apple identity token has expired.');
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            // Apple might have rotated keys, clear cache and retry once
            Cache::forget(self::CACHE_KEY);
            $jwkKeys = self::getApplePublicKeys(true);
            try {
                $decoded = JWT::decode($identityToken, $jwkKeys);
            } catch (\Throwable $retryException) {
                throw new \Exception('Invalid Apple identity token signature: ' . $retryException->getMessage());
            }
        } catch (\Throwable $e) {
            throw new \Exception('Failed to decode Apple identity token: ' . $e->getMessage());
        }

        // Verify issuer
        if (!isset($decoded->iss) || $decoded->iss !== self::APPLE_ISSUER) {
            throw new \Exception('Invalid Apple token issuer: expected ' . self::APPLE_ISSUER);
        }

        // Verify audience if configured
        $configuredAudience = config('services.apple.client_id');
        if (!empty($configuredAudience)) {
            $allowedAudiences = array_map('trim', explode(',', $configuredAudience));
            $tokenAudiences = is_array($decoded->aud) ? $decoded->aud : [$decoded->aud];

            if (empty(array_intersect($tokenAudiences, $allowedAudiences))) {
                throw new \Exception('Invalid Apple token audience.');
            }
        }

        // Verify subject (Apple user unique ID)
        if (empty($decoded->sub)) {
            throw new \Exception('Apple identity token is missing subject (sub).');
        }

        return $decoded;
    }

    /**
     * Retrieve and parse Apple public JWK keys.
     *
     * @param bool $forceRefresh
     * @return array
     * @throws \Exception
     */
    protected static function getApplePublicKeys(bool $forceRefresh = false): array
    {
        if ($forceRefresh) {
            Cache::forget(self::CACHE_KEY);
        }

        $keysData = Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            try {
                $response = Http::timeout(10)->get(self::APPLE_KEYS_URL);
                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Throwable $e) {
                Log::error('Failed to fetch Apple public keys: ' . $e->getMessage());
            }
            return null;
        });

        if (!$keysData || empty($keysData['keys'])) {
            // Attempt direct fetch without cache if cache returned empty
            try {
                $response = Http::timeout(10)->get(self::APPLE_KEYS_URL);
                if ($response->successful()) {
                    $keysData = $response->json();
                }
            } catch (\Throwable $e) {
                Log::error('Direct fetch of Apple public keys failed: ' . $e->getMessage());
            }
        }

        if (empty($keysData['keys'])) {
            throw new \Exception('Unable to retrieve Apple authentication public keys.');
        }

        return JWK::parseKeySet($keysData);
    }
}
