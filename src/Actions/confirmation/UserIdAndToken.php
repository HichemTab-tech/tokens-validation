<?php

namespace HichemtabTech\TokensValidation\Actions\confirmation;

/**
 * Class UserIdAndToken represents a user ID and token.
 */
class UserIdAndToken {
    /**
     * @var string The user ID.
     */
    private string $userId;

    /**
     * @var string The token.
     */
    private string $token;

    /**
     * UserIdAndToken constructor.
     * @param string $userId The user ID.
     * @param string $token The token.
     */
    public function __construct(string $userId, string $token) {
        $this->userId = $userId;
        $this->token = $token;
    }

    /**
     * Gets the user ID.
     *
     * @return string The user ID.
     */
    public function getUserId(): string {
        return $this->userId;
    }

    /**
     * Gets the token.
     *
     * @return string The token.
     */
    public function getToken(): string {
        return $this->token;
    }

    /**
     * @return UserIdAndTokenBuilder
     */
    public static function builder(): UserIdAndTokenBuilder
    {
        return new UserIdAndTokenBuilder();
    }
}