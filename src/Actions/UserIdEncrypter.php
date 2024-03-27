<?php /** @noinspection PhpUnused */

/** @noinspection SpellCheckingInspection */

namespace HichemtabTech\TokensValidation\Actions;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\BadFormatException;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Defuse\Crypto\Key;

/**
 *
 */
class UserIdEncrypter
{
    /**
     * you can regenerate an encypterKey by calling Key::createNewRandomKey()->saveToAsciiSafeString(), and store it here in $encrypterKey;
     * but this action is highly recommned before launching your project because once you change the key every other encryptedUserId will be invalide.
     *
     * @var string
     */
    protected static $encrypterKey = 'def00000d92d8449abd99476b92ce79555cd81fa28173ed18f0162419b8a7332a00281c2c1e6568d67e3901cfd55fafdd9112d206feebb1d7e8acaf4dc08f0d6ab576a46';

    /**
     * @param $userId
     * @return string
     * @throws BadFormatException
     * @throws EnvironmentIsBrokenException
     */
    public function encrypt($userId): string
    {
        return Crypto::encrypt($userId, Key::loadFromAsciiSafeString(self::$encrypterKey));
    }

    /**
     * @param $encryptedUserId
     * @return string
     * @throws BadFormatException
     * @throws EnvironmentIsBrokenException
     * @throws WrongKeyOrModifiedCiphertextException
     */
    public function decrypt($encryptedUserId): string {
        return Crypto::decrypt($encryptedUserId, Key::loadFromAsciiSafeString(self::$encrypterKey));
    }
}