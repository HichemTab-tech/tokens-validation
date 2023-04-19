<?php

namespace HichemtabTech\TokensValidation\Actions\authentication;

use HichemtabTech\TokensValidation\Model\authentication\AuthToken;
use HichemtabTech\TokensValidation\TokensValidation;

/**
 *
 */
class AuthTokenCookiesHandler
{
    /**
     * @param AuthToken $authToken
     * @return void
     */
    public function save(AuthToken $authToken): void
    {
        setcookie(sha1('auth_token'), $authToken->getContent(), time()+TokensValidation::getAuthTokenExpirationDelay(), "/");
    }

    /**
     * @return string|null
     */
    public function get(): ?string
    {
        $token = htmlspecialchars($_COOKIE[sha1('auth_token')] ?? "");
        return $token != "" ? $token : null;
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        setcookie(sha1('auth_token'), "", time()+TokensValidation::getAuthTokenExpirationDelay(), "/");
    }
}