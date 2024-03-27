<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Results\Invitation;

use HichemtabTech\TokensValidation\Results\BaseResults;
use HichemtabTech\TokensValidation\Results\BaseResultsBuilder;

class InvitationResponse extends BaseResults
{
    /**
     * @var string|null
     */
    private $whatFor;

    /**
     * @var string|null
     */
    private $userId;

    /**
     * @var string|null
     */
    private $target_email;

    /**
     * @var string|null
     */
    private $data;

    public function __construct(InvitationResponseBuilder $builder)
    {
        parent::__construct($builder->isValidationSucceed(), $builder->getCause());
        $this->whatFor = $builder->getWhatFor();
        $this->userId = $builder->getUserId();
        $this->target_email = $builder->getTargetEmail();
        $this->data = $builder->getData();
    }

    /**
     * Returns the purpose of the invitation.
     *
     * @return string|null The purpose.
     */
    public function getWhatFor(): ?string
    {
        return $this->whatFor;
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
     * Returns the target email of the invitation.
     *
     * @return string|null The target email.
     */
    public function getTargetEmail(): ?string
    {
        return $this->target_email;
    }

    /**
     * Returns the data associated with the invitation.
     *
     * @return string|null The data.
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * Creates a new InvitationResponse object using the builder pattern.
     *
     * @return BaseResultsBuilder The builder object.
     */
    public static function builder(): BaseResultsBuilder
    {
        return new InvitationResponseBuilder();
    }
}