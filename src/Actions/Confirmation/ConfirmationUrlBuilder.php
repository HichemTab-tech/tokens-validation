<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation\Actions\Confirmation;

use HichemtabTech\TokensValidation\Model\Confirmation\ConfirmationToken;

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
        $p = parse_url($baseUrl);
        parse_str($p['query']??"", $r);
        $r['u'] = $confirmationToken->getUserId();
        $r['c'] = $confirmationToken->getContent();
        $q = http_build_query($r);
        return $p['scheme'] . '://' . $p['host'] . $p['path']
            . (!empty($q) ? '?' . $q : '');
    }

    /**
     * @param string $url
     * @return UserIdAndToken
     */
    public function getUserIdAndTokenFromUrl(string $url): UserIdAndToken
    {
        $p = parse_url($url);
        parse_str($p['query']??"", $r);
        return UserIdAndToken::builder()
            ->setUserId($r["u"]??"")
            ->setToken($r["c"]??"")
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