<?php

namespace HichemtabTech\TokensValidation\Results;

use Exception;

/**
 * Class BaseResultsBuilder
 *
 * Builder class for the BaseResults object.
 */
class BaseResultsBuilder
{
    private bool $validationSucceed;
    private ?string $cause;
    private ?Exception $exception;

    /**
     * BaseResultsBuilder constructor.
     */
    public function __construct()
    {
        $this->validationSucceed = false;
        $this->cause = null;
        $this->exception = null;
    }

    /**
     * Sets the validation success value.
     *
     * @param bool $validationSucceed Indicates if the validation process succeeded or not.
     * @return $this This instance of the BaseResultsBuilder object.
     */
    public function setValidationSucceed(bool $validationSucceed): self
    {
        $this->validationSucceed = $validationSucceed;
        return $this;
    }

    /**
     * Sets the cause of a validation failure.
     *
     * @param string|null $cause Contains a message explaining why the validation process failed.
     * @return $this This instance of the BaseResultsBuilder object.
     */
    public function setCause(?string $cause): self
    {
        $this->cause = $cause;
        return $this;
    }

    /**
     * Sets the exception caused a validation failure.
     *
     * @param Exception|null $exception Contains an exception explaining why the validation process failed.
     * @return $this This instance of the BaseResultsBuilder object.
     */
    public function setException(?Exception $exception): self
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * Builds the BaseResults object with the provided values.
     *
     * @return BaseResults The resulting BaseResults object.
     */
    public function build(): BaseResults
    {
        return new BaseResults($this->validationSucceed, $this->cause, $this->exception);
    }

    /**
     * @return bool
     */
    public function isValidationSucceed(): bool
    {
        return $this->validationSucceed;
    }

    /**
     * @return string|null
     */
    public function getCause(): ?string
    {
        return $this->cause;
    }
}