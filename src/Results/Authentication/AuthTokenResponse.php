<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Results\Authentication;

use HichemtabTech\TokensValidation\Model\Authentication\AuthToken;
use HichemtabTech\TokensValidation\Results\BaseResults;

/**
 * Represents the response of an authentication token request.
 */
class AuthTokenResponse extends BaseResults
{
    /**
     * The user ID associated with the token.
     *
     * @var string|null
     */
    private ?string $userId;

    /**
     * The new authentication token.
     *
     * @var AuthToken|null
     */
    private AuthToken|null $newToken;

    /**
     * Creates a new instance of the AuthTokenResponse class with the specified builder.
     *
     * @param AuthTokenResponseBuilder $builder The builder to use.
     */
    public function __construct(AuthTokenResponseBuilder $builder)
    {
        parent::__construct($builder->isValidationSucceed(), $builder->getCause());
        $this->userId = $builder->getUserId();
        $this->newToken = $builder->getNewToken();
    }

    /**
     * Returns the user ID associated with the token.
     *
     * @return string|null The user ID.
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * Returns the new authentication token.
     *
     * @return AuthToken|null The token.
     */
    public function getNewToken(): ?AuthToken
    {
        return $this->newToken;
    }

    /**
     * @return AuthTokenResponseBuilder
     */
    public static function builder(): AuthTokenResponseBuilder
    {
        return new AuthTokenResponseBuilder();
    }
}