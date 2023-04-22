<?php

namespace HichemtabTech\TokensValidation\Model\Authentication;

use DateTime;

/**
 *
 */
class AuthToken
{
    /**
     * @var string $userId
     */
    private string $userId;
    /**
     * @var integer $type
     */
    private int $type;
    /**
     * @var string $content
     */
    private string $content;
    /**
     * @var DateTime $expiredAt
     */
    private DateTime $expiredAt;
    /**
     * @var string $userAgent
     */
    private string $userAgent;

    /**
     * @var string $fingerprint
     */
    private string $fingerprint;

    /**
     * @param $userId
     * @param $type
     * @param $content
     * @param $expiredAt
     * @param $userAgent
     * @param $fingerprint
     */
    public function __construct($userId, $type, $content, $expiredAt, $userAgent, $fingerprint)
    {
        $this->userId = $userId;
        $this->type = $type;
        $this->content = $content;
        $this->expiredAt = $expiredAt;
        $this->userAgent = $userAgent;
        $this->fingerprint = $fingerprint;
    }

    /**
     * @return AuthTokenBuilder
     */
    public static function builder(): AuthTokenBuilder
    {
        return new AuthTokenBuilder();
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return DateTime
     */
    public function getExpiredAt(): DateTime
    {
        return $this->expiredAt;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    public function getFingerprint(): string
    {
        return $this->fingerprint;
    }
}