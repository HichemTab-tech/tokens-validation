<?php

namespace HichemtabTech\TokensValidation;

use DateTime;
use Exception;
use HichemtabTech\TokensValidation\Actions\authentication\AuthTokenCookiesHandler;
use HichemtabTech\TokensValidation\Actions\authentication\AuthTokenGenerator;
use HichemtabTech\TokensValidation\Actions\confirmation\ConfirmationCodeGenerator;
use HichemtabTech\TokensValidation\Actions\confirmation\ConfirmationUrlBuilder;
use HichemtabTech\TokensValidation\Actions\confirmation\UserIdAndToken;
use HichemtabTech\TokensValidation\Actions\UserIdEncrypter;
use HichemtabTech\TokensValidation\Model\authentication\AuthToken;
use HichemtabTech\TokensValidation\Model\authentication\AuthTokenModel;
use HichemtabTech\TokensValidation\Model\confirmation\ConfirmationsTokenTypes;
use HichemtabTech\TokensValidation\Model\confirmation\ConfirmationToken;
use HichemtabTech\TokensValidation\Model\confirmation\ConfirmationTokenModel;
use HichemtabTech\TokensValidation\results\authentication\AuthTokenResponse;
use HichemtabTech\TokensValidation\results\confirmation\ConfirmationTokenResponse;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

/**
 *
 */
class TokensValidation
{
    /**
     * @var string $DB_NAME
     */
    private static string $DB_NAME;

    /**
     * @var string $DB_PASS
     */
    private static string $DB_PASS;

    /**
     * @var string $DB_USER
     */
    private static string $DB_USER;

    /**
     * @var string $DB_HOST
     */
    private static string $DB_HOST;

    /**
     * @var float|int
     */
    private static int|float $AuthTokenExpirationDelay = 60*60*24*7;
    /**
     * @var float|int
     */
    private static int|float $ConfirmationTokenExpirationDelay = 60*10;

    /**
     * class that extend ConfirmationUrlBuilder::class
     * @see ConfirmationUrlBuilder::class to create a simlaire custom class
     * @var string
     */
    public static string $ConfirmationUrlBuilder = ConfirmationUrlBuilder::class;
    /**
     * class that extend ConfirmationCodeGenerator::class
     * @see ConfirmationCodeGenerator::class to create a simlaire custom class
     * @var string
     */
    public static string $ConfirmationCodeGenerator = ConfirmationCodeGenerator::class;
    /**
     * class that extend AuthTokenGenerator::class
     * @see AuthTokenGenerator::class to create a simlaire custom class
     * @var string
     */
    public static string $AuthTokenGenerator = AuthTokenGenerator::class;
    /**
     * class that extend AuthTokenCookiesHandler::class
     * @see AuthTokenCookiesHandler::class to create a simlaire custom class
     * @var string
     */
    public static string $AuthTokenCookiesHandler = AuthTokenCookiesHandler::class;
    /**
     * class that extend UserIdEncrypter::class
     * @see UserIdEncrypter::class to create a simlaire custom class
     * @var string
     */
    public static string $UserIdEncrypter = UserIdEncrypter::class;

    /**
     * @return float|int
     */
    public static function getAuthTokenExpirationDelay(): float|int
    {
        return self::$AuthTokenExpirationDelay;
    }

    /**
     * @param float|int $AuthTokenExpirationDelay
     * @noinspection PhpUnused
     */
    public static function setAuthTokenExpirationDelay(float|int $AuthTokenExpirationDelay): void
    {
        self::$AuthTokenExpirationDelay = $AuthTokenExpirationDelay;
    }

    /**
     * @return float|int
     */
    public static function getConfirmationTokenExpirationDelay(): float|int
    {
        return self::$ConfirmationTokenExpirationDelay;
    }

    /**
     * @param float|int $ConfirmationTokenExpirationDelay
     * @noinspection PhpUnused
     */
    public static function setConfirmationTokenExpirationDelay(float|int $ConfirmationTokenExpirationDelay): void
    {
        self::$ConfirmationTokenExpirationDelay = $ConfirmationTokenExpirationDelay;
    }

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @param string $DB_HOST
     */
    public static function setDBHOST(string $DB_HOST): void
    {
        self::$DB_HOST = $DB_HOST;
    }

    /**
     * @param string $DB_NAME
     */
    public static function setDBNAME(string $DB_NAME): void
    {
        self::$DB_NAME = $DB_NAME;
    }

    /**
     * @param string $DB_USER
     */
    public static function setDBUSER(string $DB_USER): void
    {
        self::$DB_USER = $DB_USER;
    }

    /**
     * @param string $DB_PASS
     */
    public static function setDBPASS(string $DB_PASS): void
    {
        self::$DB_PASS = $DB_PASS;
    }

    /**
     * @return string
     */
    public static function getDBHOST(): string
    {
        return self::$DB_HOST;
    }

    /**
     * @return string
     */
    public static function getDBNAME(): string
    {
        return self::$DB_NAME;
    }

    /**
     * @return string
     */
    public static function getDBUSER(): string
    {
        return self::$DB_USER;
    }

    /**
     * @return string
     */
    public static function getDBPASS(): string
    {
        return self::$DB_PASS;
    }


    /**
     * @return void
     */
    public static function prepare(): void
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => self::getDBHOST(),
            'database' => self::getDBNAME(),
            'username' => self::getDBUSER(),
            'password' => self::getDBPASS(),
        ]);

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();


        if (!Capsule::schema()->hasTable('auth_tokens')) {
            Capsule::schema()->create('auth_tokens', function (Blueprint $table) {
                $table->id();
                $table->string('userId');
                $table->string('content');
                $table->smallInteger('type');
                $table->string('userAgent');
                $table->string('fingerprint');
                $table->timestamp('expire_at')->useCurrent();
                $table->timestamps();
            });
        }


        if (!Capsule::schema()->hasTable('confirmation_tokens')) {
            Capsule::schema()->create('confirmation_tokens', function (Blueprint $table) {
                $table->id();
                $table->string('userId');
                $table->string('content');
                $table->string('whatFor');
                $table->smallInteger('type');
                $table->timestamp('expire_at')->useCurrent();
                $table->timestamps();
            });
        }
    }

    /**
     * @param string $userId
     * @param integer $type
     * @param string|null $oldToken
     * @param string $fingerPrint
     * @param int|null $confirmationType
     * @param string $whatFor
     * @return AuthToken|ConfirmationToken
     * @throws Exception
     */
    private static function createNewToken(string $userId, int $type, string $oldToken = null, string $fingerPrint = "", int $confirmationType = null, string $whatFor = "default"): ConfirmationToken|AuthToken
    {
        $datetime = new DateTime();
        if ($type == TokensTypes::AUTHENTICATION_BY_TOKEN || $type == TokensTypes::AUTHENTICATION_BY_COOKIE) {
            $datetime->modify('+'.self::getAuthTokenExpirationDelay().' seconds');
            $authToken = AuthToken::builder()
                ->withUserId($userId)
                ->withType($type)
                ->withContent(call_user_func_array([new self::$AuthTokenGenerator(), 'generate'], [$userId]))
                ->withExpiredAt($datetime)
                ->withUserAgent(($_SERVER['HTTP_USER_AGENT'] ?? ''))
                ->withFingerprint($fingerPrint)
                ->build();
            if ($oldToken != null) {
                AuthTokenModel::where("content", $oldToken)->where('userId', $userId)->update([
                    'userId' => $authToken->getUserId(),
                    'content' => $authToken->getContent(),
                    'type' => $authToken->getType(),
                    'expire_at' => $authToken->getExpiredAt(),
                ]);
            }
            else{
                AuthTokenModel::create([
                    'userId' => $authToken->getUserId(),
                    'content' => $authToken->getContent(),
                    'type' => $authToken->getType(),
                    'expire_at' => $authToken->getExpiredAt(),
                    'userAgent' => $authToken->getUserAgent(),
                    'fingerprint' => $authToken->getFingerprint()
                ]);
            }
            if ($type == TokensTypes::AUTHENTICATION_BY_COOKIE) {
                call_user_func_array([new self::$AuthTokenCookiesHandler(), 'save'], [$authToken]);
            }
            return $authToken;
        }
        else{
            if ($confirmationType == null) throw new Exception("confirmationType is null");
            $datetime->modify('+'.self::getConfirmationTokenExpirationDelay().' seconds');
            $encryptedUserId = call_user_func_array([new self::$UserIdEncrypter(), 'encrypt'], [$userId]);
            $token = ConfirmationToken::builder()
                ->withUserId($encryptedUserId)
                ->withType($confirmationType)
                ->withContent(call_user_func_array([new self::$ConfirmationCodeGenerator(), 'generate'], [$confirmationType]))
                ->withExpiredAt($datetime)
                ->withWhatFor($whatFor)
                ->build();
            ConfirmationTokenModel::create([
                'userId' => $userId,
                'content' => $token->getContent(),
                'type' => $token->getType(),
                'expire_at' => $token->getExpiredAt(),
                'whatFor' => $whatFor
            ]);


            return $token;
        }
    }

    /**
     * @param string $userId
     * @param string $fingerPrint
     * @param bool $usingCookies
     * @return AuthToken
     * @throws Exception
     * @noinspection PhpUnused
     */
    public static function createNewAuthToken(string $userId, string $fingerPrint = "", bool $usingCookies = false): AuthToken
    {
        return self::createNewToken(userId: $userId, type: $usingCookies ? TokensTypes::AUTHENTICATION_BY_COOKIE : TokensTypes::AUTHENTICATION_BY_TOKEN, fingerPrint: $fingerPrint);
    }

    /**
     * @param string $userId
     * @param int $confirmationType
     * @param string $whatFor
     * @return ConfirmationToken
     * @throws Exception
     * @noinspection PhpUnused
     */
    public static function createNewConfirmationToken(string $userId, int $confirmationType = ConfirmationsTokenTypes::SMALL_CODE, string $whatFor = "default"): ConfirmationToken
    {
        return self::createNewToken(userId: $userId, type: TokensTypes::CONFIRMATION_CODE, confirmationType: $confirmationType, whatFor: $whatFor);
    }

    /**
     * @param integer $limit
     * @param boolean $justNumbers
     * @return string
     */
    public static function generateAlphaNumKey(int $limit = 12, bool $justNumbers = false): string
    {
        $characters = '0123456789';
        if (!$justNumbers) {
            $characters .= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $characters = str_split($characters);
        for ($i = 0; $i < 3; $i++) {
            shuffle($characters);
            $characters = array_merge($characters, $characters);
        }
        $randstring = '';
        for ($i = 0; $i < $limit; $i++) {
            $randstring .= $characters[rand(0, count($characters) - 1)];
        }
        return $randstring;
    }

    /**
     * @param string $fingerPrint
     * @param string|null $authToken
     * @param bool $regenerate
     * @return AuthTokenResponse
     * @throws Exception
     */
    private static function checkAuthToken_(string $fingerPrint, ?string $authToken, bool $regenerate): AuthTokenResponse
    {
        $authTokenResultBuilder = AuthTokenResponse::builder()
            ->setValidationSucceed(false);
        if ($authToken == null) {
            $token = call_user_func_array([new self::$AuthTokenCookiesHandler(), 'get'], []);
        }
        if (isset($token) AND $token != null) {
            $authTokenModel = AuthTokenModel::where('content', '=', $token)->get();
            if ($authTokenModel != null && count($authTokenModel) != 0) {
                $authTokenModel = $authTokenModel[0];
                $authTokenResultBuilder->setUserId($authTokenModel->userId);
                if (!self::isExpired(DateTime::createFromFormat('Y-m-d H:i:s', $authTokenModel->expire_at))) {

                    if ($authTokenModel->fingerprint == '' || $authTokenModel->fingerprint == $fingerPrint) {
                        $authTokenResultBuilder->setValidationSucceed(true);
                        if ($regenerate) {
                            $newToken = self::regenerateAuthToken($authTokenModel->userId, $token, $authTokenModel->type);
                            $authTokenResultBuilder->setNewToken($newToken);
                        }
                        return $authTokenResultBuilder->build();
                    }
                    else{
                        $authTokenResultBuilder->setCause("FINGERPRINT_INVALID");
                    }
                }
                else{
                    $authTokenResultBuilder->setCause("TOKEN_EXPIRED");
                }
                AuthTokenModel::where('content', '=', $token)->delete();
            }
            else{
                $authTokenResultBuilder->setCause("TOKEN_INVALID");
            }
            call_user_func_array([new self::$AuthTokenCookiesHandler(), 'delete'], [$authToken]);
        }
        else{
            $authTokenResultBuilder->setCause("NO_TOKEN_PROVIDED");
        }
        return $authTokenResultBuilder->build();
    }

    /**
     * @param string $fingerPrint
     * @param string|null $authToken
     * @return AuthTokenResponse
     * @throws Exception
     * @noinspection PhpUnused
     */
    public static function checkAuthTokenWithoutRegenerate(string $fingerPrint = "", ?string $authToken = null): AuthTokenResponse
    {
        return self::checkAuthToken_($fingerPrint, $authToken, false);
    }

    /**
     * @param string $fingerPrint
     * @param string|null $authToken
     * @return AuthTokenResponse
     * @throws Exception
     * @noinspection PhpUnused
     */
    public static function checkAuthToken(string $fingerPrint = "", ?string $authToken = null): AuthTokenResponse
    {
        return self::checkAuthToken_($fingerPrint, $authToken, true);
    }

    /**
     * @param string $fingerPrint
     * @param string|null $authToken
     * @return void
     * @throws Exception
     * @noinspection PhpUnused
     */
    public static function checkAuthTokenOrDie(string $fingerPrint = "", ?string $authToken = null): void
    {
        $AuthTokenResponse = self::checkAuthToken_($fingerPrint, $authToken, true);
        if (!$AuthTokenResponse->isValidationSucceed()) {
            die("AUTHENTICATION_BY_TOKEN_FAILED");
        }
    }

    /**
     * @param string $code
     * @param string|null $encryptedUserId
     * @param string $whatFor
     * @return ConfirmationTokenResponse
     */
    public static function checkConfirmationCode(string $code, string $encryptedUserId = null, string $whatFor = "default"): ConfirmationTokenResponse
    {
        $confirmationTokenResultsBuilder = ConfirmationTokenResponse::builder()
            ->setValidationSucceed(false);
        if ($code != null) {
            $modelBuilder = ConfirmationTokenModel::where('content', '=', $code);
            if ($encryptedUserId != null) {
                try {
                    $userId = call_user_func_array([new self::$UserIdEncrypter(), 'decrypt'], [$encryptedUserId]);
                } catch (Exception $e) {
                    $confirmationTokenResultsBuilder->setException($e);
                    $confirmationTokenResultsBuilder->setCause("TOKEN_UID_INVALID");
                    return $confirmationTokenResultsBuilder->build();
                }
                $modelBuilder->where('userId', '=', $userId);
            }
            $confirmationTokenModel = $modelBuilder->get();
            if ($confirmationTokenModel != null AND count($confirmationTokenModel) != 0) {
                $confirmationTokenModel = $confirmationTokenModel[0];
                $confirmationTokenResultsBuilder->withUserId($confirmationTokenModel->userId);
                if (!self::isExpired(DateTime::createFromFormat('Y-m-d H:i:s', $confirmationTokenModel->expire_at))) {
                    if ($confirmationTokenModel->whatFor == $whatFor || $whatFor == "default") {
                        $confirmationTokenResultsBuilder->setValidationSucceed(true);
                        ConfirmationTokenModel::find($confirmationTokenModel->id)->delete();
                        return $confirmationTokenResultsBuilder->build();
                    }
                    else{
                        $confirmationTokenResultsBuilder->setCause("REASON_INVALID");
                    }
                }
                else{
                    $confirmationTokenResultsBuilder->setCause("TOKEN_EXPIRED");
                }
                ConfirmationTokenModel::where('content', '=', $code)->delete();
            }
            else{
                $confirmationTokenResultsBuilder->setCause("TOKEN_INVALID");
            }
        }
        else{
            $confirmationTokenResultsBuilder->setCause("NO_TOKEN_PROVIDED");
        }
        return $confirmationTokenResultsBuilder->build();
    }

    /**
     * @param string $url
     * @param string $whatFor
     * @return ConfirmationTokenResponse
     */
    public static function checkConfirmationUrl(string $url, string $whatFor = "default"): ConfirmationTokenResponse
    {
        /** @var UserIdAndToken $userIdAndToken */
        $userIdAndToken = call_user_func_array([new TokensValidation::$ConfirmationUrlBuilder(), 'getUserIdAndTokenFromUrl'], [$url]);
        if ($userIdAndToken != null) {
            return self::checkConfirmationCode($userIdAndToken->getToken(), $userIdAndToken->getUserId(), $whatFor);
        }
        return ConfirmationTokenResponse::builder()
            ->setException(new Exception("can't get userIdAndToken"))
            ->setValidationSucceed(false)
            ->setCause("EXCEPTION")
            ->build();
    }

    /**
     * @param array $_GET_ARRAY
     * @param string $whatFor
     * @return ConfirmationTokenResponse
     */
    public static function checkConfirmationUrlParamsFromGET(array $_GET_ARRAY, string $whatFor = "default"): ConfirmationTokenResponse
    {
        /** @var UserIdAndToken $userIdAndToken */
        $userIdAndToken = call_user_func_array([new TokensValidation::$ConfirmationUrlBuilder(), 'getUserIdAndTokenFromGET'], [$_GET_ARRAY]);
        if ($userIdAndToken != null) {
            return self::checkConfirmationCode($userIdAndToken->getToken(), $userIdAndToken->getUserId(), $whatFor);
        }
        return ConfirmationTokenResponse::builder()
            ->setException(new Exception("can't get userIdAndToken"))
            ->setValidationSucceed(false)
            ->setCause("EXCEPTION")
            ->build();
    }

    /**
     * @param DateTime $expirationDate
     * @return bool
     */
    private static function isExpired(DateTime $expirationDate): bool
    {
        if ($expirationDate < new DateTime()) {
            return true;
        }
        return false;
    }

    /**
     * @param $userId
     * @param $oldToken
     * @param $tokenType
     * @return AuthToken
     * @throws Exception
     */
    private static function regenerateAuthToken($userId, $oldToken, $tokenType): AuthToken
    {
        return self::createNewToken(userId: $userId, type: $tokenType, oldToken: $oldToken);
    }
}