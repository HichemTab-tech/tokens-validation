<?php

namespace HichemtabTech\TokensValidation\Results\Confirmation;

use HichemtabTech\TokensValidation\Results\BaseResults;

/**
 * Represents a response to a confirmation token request.
 */
class ConfirmationTokenResponse extends BaseResults
{
    /**
     * @var string|null
     */
    private ?string $userId;
    /**
     * @var string|null
     */
    private ?string $whatFor;

    public function __construct(ConfirmationTokenResponseBuilder $builder)
    {
        parent::__construct($builder->isValidationSucceed(), $builder->getCause());
        $this->userId = $builder->getUserId();
        $this->whatFor = $builder->getWhatFor();
    }

    /**
     * Returns the ID of the user associated with this response.
     *
     * @return string|null The user ID.
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * Sets the ID of the user associated with this response.
     *
     * @param string|null $userId The user ID.
     * @return ConfirmationTokenResponse The updated response object.
     */
    public function setUserId(?string $userId): ConfirmationTokenResponse
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Returns the purpose of the confirmation token.
     *
     * @return string|null The purpose.
     */
    public function getWhatFor(): ?string
    {
        return $this->whatFor;
    }

    /**
     * Sets the purpose of the confirmation token.
     *
     * @param string|null $whatFor The purpose.
     * @return ConfirmationTokenResponse The updated response object.
     */
    public function setWhatFor(?string $whatFor): ConfirmationTokenResponse
    {
        $this->whatFor = $whatFor;
        return $this;
    }

    /**
     * Creates a new ConfirmationTokenResponse object using the builder pattern.
     *
     * @return ConfirmationTokenResponseBuilder The builder object.
     */
    public static function builder(): ConfirmationTokenResponseBuilder
    {
        return new ConfirmationTokenResponseBuilder();
    }
}