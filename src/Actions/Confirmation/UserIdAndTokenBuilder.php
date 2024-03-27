<?php

namespace HichemtabTech\TokensValidation\Actions\Confirmation;

use RuntimeException;

/**
 * Class UserIdAndTokenBuilder is a builder for creating instances of the UserIdAndToken class.
 */
class UserIdAndTokenBuilder {
    /**
     * @var string|null The user ID.
     */
    private ?string $userId = null;

    /**
     * @var string|null The token.
     */
    private ?string $token = null;

    /**
     * Sets the user ID.
     *
     * @param string $userId The user ID.
     * @return $this The builder instance.
     */
    public function setUserId(string $userId): self {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Sets the token.
     *
     * @param string $token The token.
     * @return $this The builder instance.
     */
    public function setToken(string $token): self {
        $this->token = $token;
        return $this;
    }

    /**
     * Builds and returns a new instance of the UserIdAndToken class.
     *
     * @return UserIdAndToken The new UserIdAndToken instance.
     * @throws RuntimeException If the user ID or token is not set.
     */
    public function build(): UserIdAndToken {
        if (!$this->userId) {
            throw new RuntimeException('User ID must be set.');
        }

        if (!$this->token) {
            throw new RuntimeException('Token must be set.');
        }

        return new UserIdAndToken($this->userId, $this->token);
    }
}