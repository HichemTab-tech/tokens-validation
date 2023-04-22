<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Results;

use Exception;
use HichemtabTech\TokensValidation\BaseResultsBuilder;

/**
 * Class BaseResults
 *
 * This class represents the results of a validation process.
 */
class BaseResults
{
    /**
     * @var bool Indicates if the validation process succeeded or not.
     */
    private bool $validationSucceed;

    /**
     * @var string|null Contains a message explaining why the validation process failed.
     */
    private ?string $cause;

    /**
     * @var Exception|null Contains an exception explaining why the validation process failed.
     */
    private ?Exception $exception;

    /**
     * BaseResults constructor.
     *
     * @param bool $validationSucceed Indicates if the validation process succeeded or not.
     * @param string|null $cause Contains a message explaining why the validation process failed.
     */
    public function __construct(bool $validationSucceed, ?string $cause = null, ?Exception $exception = null)
    {
        $this->validationSucceed = $validationSucceed;
        $this->cause = $cause;
        $this->exception = $exception;
    }

    /**
     * Getter method for the validation result.
     *
     * @return bool Indicates if the validation process succeeded or not.
     */
    public function isValidationSucceed(): bool
    {
        return $this->validationSucceed;
    }

    /**
     * Getter method for the cause of a validation failure.
     *
     * @return string|null Contains a message explaining why the validation process failed.
     */
    public function getCause(): ?string
    {
        return $this->cause;
    }

    /**
     * Getter method for the exception caused a validation failure.
     *
     * @return Exception|null Contains an exception explaining why the validation process failed.
     */
    public function getException(): ?Exception
    {
        return $this->exception;
    }

    /**
     * Creates a new BaseResultsBuilder object to build a new BaseResults instance.
     *
     * @return BaseResultsBuilder The new BaseResultsBuilder instance.
     */
    public static function builder(): BaseResultsBuilder
    {
        return new BaseResultsBuilder();
    }
}