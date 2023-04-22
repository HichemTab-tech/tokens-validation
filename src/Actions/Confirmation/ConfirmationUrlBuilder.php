<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Actions\Confirmation;

use HichemtabTech\TokensValidation\Model\Confirmation\ConfirmationToken;
use Purl\Url;

/**
 *
 */
class ConfirmationUrlBuilder
{

    /**
     * @return ConfirmationUrlBuilder
     */
    public static function instance(): ConfirmationUrlBuilder
    {
        return new ConfirmationUrlBuilder();
    }

    public function __construct()
    {
    }

    /**
     * @param ConfirmationToken $confirmationToken
     * @param string $baseUrl
     * @return string
     */
    public function getUrl(ConfirmationToken $confirmationToken, string $baseUrl): string
    {
        $url = new Url($baseUrl);
        $url->query->set("u", $confirmationToken->getUserId());// for userId
        $url->query->set("c", $confirmationToken->getContent());// for Code
        return $url->getUrl();
    }

    /**
     * @param string $url
     * @return UserIdAndToken
     */
    public function getUserIdAndTokenFromUrl(string $url): UserIdAndToken
    {
        $url = new Url($url);
        return UserIdAndToken::builder()
            ->setUserId($url->query->get("u"))
            ->setToken($url->query->get("c"))
            ->build();
    }

    /**
     * @param array $_GET_ARRAY
     * @return UserIdAndToken
     */
    public function getUserIdAndTokenFromGET(array $_GET_ARRAY): UserIdAndToken
    {
        return UserIdAndToken::builder()
            ->setUserId($_GET_ARRAY["u"]??"")
            ->setToken($_GET_ARRAY["c"]??"")
            ->build();
    }
}