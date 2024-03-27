<?php

namespace HichemtabTech\TokensValidation\Model\Authentication;

use DateTime;

/**
 *
 */
class AuthTokenBuilder
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @var DateTime
     */
    private $expiredAt;

    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var string
     */
    private $fingerprint;

    /**
     * @param string $userId
     * @return $this
     */
    public function withUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function withType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function withContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param DateTime $expiredAt
     * @return $this
     */
    public function withExpiredAt(DateTime $expiredAt): self
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }

    /**
     * @param string $userAgent
     * @return $this
     */
    public function withUserAgent(string $userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * @param string $fingerprint
     * @return $this
     */
    public function withFingerprint(string $fingerprint): self
    {
        $this->fingerprint = $fingerprint;
        return $this;
    }

    /**
     * @return AuthToken
     */
    public function build(): AuthToken
    {
        return new AuthToken(
            $this->userId,
            $this->type,
            $this->content,
            $this->expiredAt,
            $this->userAgent,
            $this->fingerprint
        );
    }
}
