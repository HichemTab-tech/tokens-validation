<?php

namespace HichemtabTech\TokensValidation\results\confirmation;

use HichemtabTech\TokensValidation\BaseResultsBuilder;

/**
 * Class ConfirmationTokenResponseBuilder
 */
class ConfirmationTokenResponseBuilder extends BaseResultsBuilder
{
    /**
     * @var string|null
     */
    private ?string $userId;

    /**
     * @var string|null
     */
    private ?string $whatFor;

    public function __construct()
    {
        parent::__construct();
        $this->userId = null;
        $this->whatFor = null;
    }

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
     * @param string|null $whatFor
     * @return $this
     */
    public function withWhatFor(?string $whatFor): self
    {
        $this->whatFor = $whatFor;
        return $this;
    }

    /**
     * @return ConfirmationTokenResponse
     */
    public function build(): ConfirmationTokenResponse
    {
        return new ConfirmationTokenResponse($this);
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @return string|null
     */
    public function getWhatFor(): ?string
    {
        return $this->whatFor;
    }
}