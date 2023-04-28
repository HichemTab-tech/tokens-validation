<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Model\Invitation;

use DateTime;

/**
 * Class InvitationBuilder
 * A builder class for creating Invitation objects.
 */
class InvitationBuilder
{
    /**
     * @var string|null $id The invitation ID.
     */
    private ?string $id = null;

    /**
     * @var string|null $userId The user ID associated with the invitation.
     */
    private ?string $userId;

    /**
     * @var string|null $targetEmail The email address that the invitation is being sent to.
     */
    private ?string $targetEmail = null;

    /**
     * @var string|null $content The message content of the invitation.
     */
    private ?string $content = null;

    /**
     * @var DateTime|null $expireAt The expiration date and time of the invitation.
     */
    private ?DateTime $expireAt = null;

    /**
     * @var string|null $whatFor The purpose of the invitation.
     */
    private ?string $whatFor = null;

    /**
     * @var string|null $data Additional data associated with the invitation.
     */
    private ?string $data = null;

    /**
     * @var bool $accepted Whether the invitation has been accepted or not.
     */
    private bool $accepted = false;

    /**
     * @param string|null $userId
     */
    public function __construct(?string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Sets the ID of the invitation.
     *
     * @param string $id The invitation ID.
     * @return InvitationBuilder
     */
    public function withId(string $id): InvitationBuilder
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Sets the user ID associated with the invitation.
     *
     * @param string $userId The user ID.
     * @return InvitationBuilder
     */
    public function withUserId(string $userId): InvitationBuilder
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Sets the email address that the invitation is being sent to.
     *
     * @param string $targetEmail The email address.
     * @return InvitationBuilder
     */
    public function withTargetEmail(string $targetEmail): InvitationBuilder
    {
        $this->targetEmail = $targetEmail;
        return $this;
    }

    /**
     * Sets the message content of the invitation.
     *
     * @param string $content The message content.
     * @return InvitationBuilder
     */
    public function withContent(string $content): InvitationBuilder
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Sets the expiration date and time of the invitation.
     *
     * @param DateTime $expireAt The expiration date and time.
     * @return InvitationBuilder
     */
    public function withExpireAt(DateTime $expireAt): InvitationBuilder
    {
        $this->expireAt = $expireAt;
        return $this;
    }

    /**
     * Sets the purpose of the invitation.
     *
     * @param string $whatFor The purpose of the invitation.
     * @return InvitationBuilder
     */
    public function withWhatFor(string $whatFor): InvitationBuilder
    {
        $this->whatFor = $whatFor;
        return $this;
    }

    /**
     * Sets additional data associated with the invitation.
     *
     * @param string $data Additional data.
     * @return InvitationBuilder
     */
    public function withData(string $data): InvitationBuilder
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set whether the invitation has been accepted.
     *
     * @param bool $accepted Whether the invitation has been accepted.
     * @return $this
     */
    public function withAccepted(bool $accepted): InvitationBuilder
    {
        $this->accepted = $accepted;
        return $this;
    }

    /**
     * Builds and returns a new Invitation object based on the current builder state.
     *
     * @return Invitation
     */
    public function build(): Invitation
    {
        return new Invitation($this->id, $this->userId, $this->targetEmail, $this->content, $this->expireAt, $this->whatFor, $this->data, $this->accepted);
    }

}