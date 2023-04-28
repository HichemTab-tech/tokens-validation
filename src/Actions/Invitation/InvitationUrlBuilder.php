<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Actions\Invitation;

use HichemtabTech\TokensValidation\Model\Invitation\Invitation;
use Purl\Url;

class InvitationUrlBuilder
{
    /**
     * @param Invitation $invitation
     * @param string $baseUrl
     * @return string
     */
    public function getUrl(Invitation $invitation, string $baseUrl): string
    {
        $url = new Url($baseUrl);
        $url->query->set("c", $invitation->getContent());// for Code
        return $url->getUrl();
    }

    /**
     * @param string $url
     * @return string
     */
    public function getTokenFromUrl(string $url): string
    {
        $url = new Url($url);
        return $url->query->get("c");
    }

    /**
     * @param array $_GET_ARRAY
     * @return string
     */
    public function getTokenFromGET(array $_GET_ARRAY): string
    {
        return $_GET_ARRAY["c"]??"";
    }
}