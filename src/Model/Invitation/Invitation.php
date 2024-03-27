<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Model\Invitation;

use DateTime;
use HichemtabTech\TokensValidation\TokensValidation;

/**
 * Class Invitation represents an invitation sent to a user via email.
 */
class Invitation
{
    /**
     * @var string|null The unique identifier for the invitation.
     */
    protected $id;

    /**
     * @var string|null The unique identifier for the user who created the invitation.
     */
    protected $userId;

    /**
     * @var string|null The email address of the user who the invitation is being sent to.
     */
    private $target_email;

    /**
     * @var string|null The content of the invitation message.
     */
    private $content;

    /**
     * @var DateTime|null The date and time when the invitation will expire.
     */
    private $expire_at;

    /**
     * @var string|null The purpose of the invitation.
     */
    private $whatFor;

    /**
     * @var string|null Additional data related to the invitation.
     */
    private $data;

    /**
     * @var bool Whether the invitation has been accepted.
     */
    private $accepted;

    /**
     * Invitation constructor.
     * @param string|null $id The unique identifier for the invitation.
     * @param string|null $userId The unique identifier for the user who created the invitation.
     * @param string|null $target_email The email address of the user who the invitation is being sent to.
     * @param string|null $content The content of the invitation message.
     * @param DateTime|null $expire_at The date and time when the invitation will expire.
     * @param string|null $whatFor The purpose of the invitation.
     * @param string|null $data Additional data related to the invitation.
     * @param bool $accepted Whether the invitation has been accepted.
     */
    public function __construct(?string $id, ?string $userId, ?string $target_email, ?string $content, ?DateTime $expire_at, ?string $whatFor, ?string $data, bool $accepted)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->target_email = $target_email;
        $this->content = $content;
        $this->expire_at = $expire_at;
        $this->whatFor = $whatFor;
        $this->data = $data;
        $this->accepted = $accepted;
    }

    /**
     * Builder method to create an Invitation object.
     *
     * @param string $userId The user id who created the invitation.
     * @return InvitationBuilder
     */
    public static function builder(string $userId): InvitationBuilder
    {
        return new InvitationBuilder($userId);
    }

    /**
     * Get the unique identifier for the invitation.
     *
     * @return string The unique identifier for the invitation.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get the unique identifier for the user who created the invitation.
     *
     * @return string The unique identifier for the user who created the invitation.
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * Get the email address of the user who the invitation is being sent to.
     *
     * @return string The email address of the user who the invitation is being sent to.
     */
    public function getTargetEmail(): string
    {
        return $this->target_email;
    }

    /**
     * Get the content of the invitation message.
     *
     * @return string The content of the invitation message.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get the date and time when the invitation will expire.
     *
     * @return DateTime The date and time when the invitation will expire.
     */
    public function getExpireAt(): DateTime
    {
        return $this->expire_at;
    }

    /**
     * Get the purpose of the invitation.
     *
     * @return string The purpose of the invitation.
     */
    public function getWhatFor(): string
    {
        return $this->whatFor;
    }

    /**
     * Get additional data related to the invitation.
     *
     * @return string Additional data related to the invitation.
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Check whether the invitation has been accepted.
     *
     * @return bool Whether the invitation has been accepted.
     */
    public function isAccepted(): bool
    {
        return $this->accepted;
    }

    /**
     * Get the generated invitation url
     *
     * @param string $baseUrl
     * @return string
     */
    public function getUrl(string $baseUrl = ""): string
    {
        if ($baseUrl == '') {
            $baseUrl = TokensValidation::$InvitationBaseUrl;
        }
        return call_user_func_array([new TokensValidation::$InvitationUrlBuilder(), 'getUrl'], [$this, $baseUrl]);
    }
}