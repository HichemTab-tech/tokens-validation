<?php

namespace HichemtabTech\TokensValidation\results\authentication;

use HichemtabTech\TokensValidation\BaseResultsBuilder;
use HichemtabTech\TokensValidation\Model\authentication\AuthToken;

/**
 * Builds instances of the AuthTokenResponse class.
 */
class AuthTokenResponseBuilder extends BaseResultsBuilder
{
    /**
     * The user ID associated with the token.
     *
     * @var string|null
     */
    private ?string $userId;

    /**
     * The new authentication token.
     *
     * @var AuthToken|null
     */
    private ?AuthToken $newToken;

    public function __construct()
    {
        parent::__construct();
        $this->userId = null;
        $this->newToken = null;
    }

    /**
     * Sets the user ID associated with the token.
     *
     * @param string|null $userId The user ID.
     *
     * @return AuthTokenResponseBuilder This builder instance.
     */
    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Sets the new authentication token.
     *
     * @param AuthToken|null $newToken The token.
     *
     * @return AuthTokenResponseBuilder This builder instance.
     */
    public function setNewToken(?AuthToken $newToken): self
    {
        $this->newToken = $newToken;
        return $this;
    }

    /**
     * Builds and returns an instance of the AuthTokenResponse class with the configured settings.
     *
     * @return AuthTokenResponse The resulting AuthTokenResponse instance.
     */
    public function build(): AuthTokenResponse
    {
        return new AuthTokenResponse($this);
    }

    /**
     * Returns the user ID associated with the token.
     *
     * @return string|null The user ID.
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * Returns the new authentication token.
     *
     * @return AuthToken|null The token.
     */
    public function getNewToken(): ?AuthToken
    {
        return $this->newToken;
    }
}