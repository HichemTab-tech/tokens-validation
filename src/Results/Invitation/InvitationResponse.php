<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Results\Invitation;

use HichemtabTech\TokensValidation\Results\BaseResults;

class InvitationResponse extends BaseResults
{
    /**
     * @var string|null
     */
    private ?string $whatFor;

    /**
     * @var string|null
     */
    private ?string $userId;

    /**
     * @var string|null
     */
    private ?string $target_email;

    /**
     * @var string|null
     */
    private ?string $data;

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
     * @return InvitationResponseBuilder The builder object.
     */
    public static function builder(): InvitationResponseBuilder
    {
        return new InvitationResponseBuilder();
    }
}