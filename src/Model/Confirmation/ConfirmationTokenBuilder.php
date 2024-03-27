<?php

namespace HichemtabTech\TokensValidation\Model\Confirmation;

use DateTime;

class ConfirmationTokenBuilder
{
    /**
     * @var string|null $userId
     */
    private ?string $userId = null;

    /**
     * @var int|null $type
     */
    private ?int $type = null;

    /**
     * @var string|null $content
     */
    private ?string $content = null;

    /**
     * @var DateTime|null $expiredAt
     */
    private ?DateTime $expiredAt = null;

    /**
     * @var string|null $whatFor
     */
    private ?string $whatFor = null;

    /**
     * Sets the user ID for the token.
     *
     * @param string $userId
     * @return $this
     */
    public function withUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Sets the type for the token.
     *
     * @param int $type
     * @return $this
     */
    public function withType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Sets the content for the token.
     *
     * @param string $content
     * @return $this
     */
    public function withContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Sets the expiration date and time for the token.
     *
     * @param DateTime $expiredAt
     * @return $this
     */
    public function withExpiredAt(DateTime $expiredAt): self
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }

    /**
     * Sets the purpose key for the token.
     *
     * @param string $whatFor
     * @return $this
     */
    public function withWhatFor(string $whatFor): self
    {
        $this->whatFor = $whatFor;
        return $this;
    }

    /**
     * Builds and returns a new ConfirmationToken object based on the current builder state.
     *
     * @return ConfirmationToken
     */
    public function build(): ConfirmationToken
    {
        return new ConfirmationToken($this->userId, $this->type, $this->content, $this->expiredAt, $this->whatFor);
    }
}
