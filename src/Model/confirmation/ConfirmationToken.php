<?php

namespace HichemtabTech\TokensValidation\Model\confirmation;

use DateTime;
use HichemtabTech\TokensValidation\TokensValidation;

/**
 * Class ConfirmationToken
 */
class ConfirmationToken
{
    /**
     * @var string $userId
     */
    private string $userId;

    /**
     * @var int $type
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
     * @var string $whatFor
     */
    private string $whatFor;

    /**
     * ConfirmationToken constructor.
     * @param string $userId
     * @param int $type
     * @param string $content
     * @param DateTime $expiredAt
     * @param string $whatFor
     */
    public function __construct(string $userId, int $type, string $content, DateTime $expiredAt, string $whatFor)
    {
        $this->userId = $userId;
        $this->type = $type;
        $this->content = $content;
        $this->expiredAt = $expiredAt;
        $this->whatFor = $whatFor;
    }

    /**
     * Create a new instance of ConfirmationTokenBuilder
     *
     * @return ConfirmationTokenBuilder
     */
    public static function builder(): ConfirmationTokenBuilder
    {
        return new ConfirmationTokenBuilder();
    }

    /**
     * Get the user ID associated with this token
     *
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * Get the type of this token
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Get the content of this token
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get the expiration date of this token
     *
     * @return DateTime
     */
    public function getExpiredAt(): DateTime
    {
        return $this->expiredAt;
    }

    /**
     * Get a purpose key of this token
     * @return string
     */
    public function getWhatFor(): string
    {
        return $this->whatFor;
    }

    public function getUrl(string $baseUrl) {
        return call_user_func_array([new TokensValidation::$ConfirmationUrlBuilder(), 'getUrl'], [$this, $baseUrl]);
    }
}


