<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Results\Invitation;

use HichemtabTech\TokensValidation\Results\BaseResultsBuilder;

/**
 * Class InvitationResponseBuilder
 */
class InvitationResponseBuilder extends BaseResultsBuilder
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

    public function __construct()
    {
        parent::__construct();
        $this->whatFor = null;
        $this->userId = null;
        $this->target_email = null;
        $this->data = null;
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
     * @param string|null $userId
     * @return $this
     */
    public function withUserId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param string|null $target_email
     * @return $this
     */
    public function withTargetEmail(?string $target_email): self
    {
        $this->target_email = $target_email;
        return $this;
    }

    /**
     * @param string|null $data
     * @return $this
     */
    public function withData(?string $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return InvitationResponse
     */
    public function build(): InvitationResponse
    {
        return new InvitationResponse($this);
    }

    /**
     * @return string|null
     */
    public function getWhatFor(): ?string
    {
        return $this->whatFor;
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
    public function getTargetEmail(): ?string
    {
        return $this->target_email;
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }
}