<?php /** @noinspection PhpUnused */

namespace HichemtabTech\TokensValidation;

use DateTime;
use Exception;
use HichemtabTech\TokensValidation\Actions\Authentication\AuthTokenCookiesHandler;
use HichemtabTech\TokensValidation\Actions\Authentication\AuthTokenGenerator;
use HichemtabTech\TokensValidation\Actions\Confirmation\ConfirmationCodeGenerator;
use HichemtabTech\TokensValidation\Actions\Confirmation\ConfirmationUrlBuilder;
use HichemtabTech\TokensValidation\Actions\Confirmation\UserIdAndToken;
use HichemtabTech\TokensValidation\Actions\Invitation\InvitationTokenGenerator;
use HichemtabTech\TokensValidation\Actions\Invitation\InvitationUrlBuilder;
use HichemtabTech\TokensValidation\Actions\UserIdEncrypter;
use HichemtabTech\TokensValidation\Model\Authentication\AuthToken;
use HichemtabTech\TokensValidation\Model\Authentication\AuthTokenModel;
use HichemtabTech\TokensValidation\Model\Confirmation\ConfirmationsTokenTypes;
use HichemtabTech\TokensValidation\Model\Confirmation\ConfirmationToken;
use HichemtabTech\TokensValidation\Model\Confirmation\ConfirmationTokenModel;
use HichemtabTech\TokensValidation\Model\Invitation\Invitation;
use HichemtabTech\TokensValidation\Model\Invitation\InvitationModel;
use HichemtabTech\TokensValidation\Results\Authentication\AuthTokenResponse;
use HichemtabTech\TokensValidation\Results\Confirmation\ConfirmationTokenResponse;
use HichemtabTech\TokensValidation\Results\Invitation\InvitationResponse;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;

/**
 *
 */
class TokensValidation
{
    /**
     * @var string $DB_NAME
     */
    private static string $DB_NAME = '';

    /**
     * @var string $DB_PASS
     */
    private static string $DB_PASS = '';

    /**
     * @var string $DB_USER
     */
    private static string $DB_USER = '';

    /**
     * @var string $DB_HOST
     */
    private static string $DB_HOST = '';

    /**
     * @var array
     */
    private static array $config;

    /**
     * @param array $config
     */
    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }

    /**
     * @var float|int
     */
    private static int|float $AuthTokenExpirationDelay = 60*60*24*7;

    /**
     * @var float|int
     */
    private static int|float $ConfirmationTokenExpirationDelay = 60*10;

    /**
     * @var float|int
     */
    private static int|float $InvitationTokenExpirationDelay = 60*60*24*3;

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
     * class that extend InvitationUrlBuilder::class
     * @see InvitationUrlBuilder::class to create a simlaire custom class
     * @var string
     */
    public static string $InvitationUrlBuilder = InvitationUrlBuilder::class;

    /**
     * class that extend InvitationTokenGenerator::class
     * @see InvitationTokenGenerator::class to create a simlaire custom class
     * @var string
     */
    public static string $InvitationTokenGenerator = InvitationTokenGenerator::class;

    /**
     * @var string
     */
    public static string $InvitationBaseUrl = "";

    /**
     * @var string[]
     */
    private static array $features = ['AuthTokens', 'ConfirmationToken'];

    /**
     * @param string[] $features
     */
    public static function setFeatures(array $features): void
    {
        self::$features = $features;
    }

    /**
     * @return float|int
     */
    public static function getAuthTokenExpirationDelay(): float|int
    {
        return self::$AuthTokenExpirationDelay;
    }

    /**
     * @param float|int $AuthTokenExpirationDelay
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
     */
    public static function setConfirmationTokenExpirationDelay(float|int $ConfirmationTokenExpirationDelay): void
    {
        self::$ConfirmationTokenExpirationDelay = $ConfirmationTokenExpirationDelay;
    }

    /**
     * @param float|int $InvitationTokenExpirationDelay
     */
    public static function setInvitationTokenExpirationDelay(float|int $InvitationTokenExpirationDelay): void
    {
        self::$InvitationTokenExpirationDelay = $InvitationTokenExpirationDelay;
    }

    /**
     * @return float|int
     */
    public static function getInvitationTokenExpirationDelay(): float|int
    {
        return self::$InvitationTokenExpirationDelay;
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

        self::prepareConfig();

        $capsule->addConnection(self::$config['connections']['mysql']);

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();


        if (in_array('AuthTokens', self::$features)) {
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
        }


        if (in_array('ConfirmationToken', self::$features)) {
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

        if (in_array('InvitationsTokens', self::$features)) {
            if (!Capsule::schema()->hasTable('invitation_tokens')) {
                Capsule::schema()->create('invitation_tokens', function (Blueprint $table) {
                    $table->id();
                    $table->string('userId');
                    $table->string('target_email');
                    $table->string('content')->unique();
                    $table->timestamp('expire_at');
                    $table->string('whatFor');
                    $table->string('data');
                    $table->smallInteger('accepted')->default(0);
                    $table->timestamps();
                    $table->unique(['whatFor', 'target_email']);
                });
            }
        }
    }

    /**
     * @return void
     */
    private static function prepareConfig(): void
    {
        if (isset(self::$config) AND self::$config != null) {
            self::$config = array_replace_recursive(DefaultConfig::get(), self::$config);
        }
        else{
            self::$config = DefaultConfig::get();
        }

        self::$features = self::$config['features'];

        self::$AuthTokenExpirationDelay = self::$config['AuthTokens']['expirationDelay'];
        self::$AuthTokenGenerator = self::$config['AuthTokens']['AuthTokenGenerator'];
        self::$AuthTokenCookiesHandler = self::$config['AuthTokens']['AuthTokenCookiesHandler'];

        self::$ConfirmationTokenExpirationDelay = self::$config['ConfirmationToken']['expirationDelay'];
        self::$ConfirmationUrlBuilder = self::$config['ConfirmationToken']['ConfirmationUrlBuilder'];
        self::$ConfirmationCodeGenerator = self::$config['ConfirmationToken']['ConfirmationCodeGenerator'];
        self::$UserIdEncrypter = self::$config['ConfirmationToken']['UserIdEncrypter'];

        self::$InvitationTokenExpirationDelay = self::$config['InvitationToken']['expirationDelay'];
        self::$InvitationUrlBuilder = self::$config['InvitationToken']['InvitationUrlBuilder'];
        self::$InvitationTokenGenerator = self::$config['InvitationToken']['InvitationTokenGenerator'];
        self::$InvitationBaseUrl = self::$config['InvitationToken']['InvitationBaseUrl'];
    }

    /**
     * @param string $userId
     * @param integer $type
     * @param string|null $oldToken
     * @param string $fingerPrint
     * @param int|null $confirmationType
     * @param string $whatFor
     * @param float|int $expirationDelay
     * @param bool|null $singleTokenPerTime
     * @return AuthToken|ConfirmationToken
     * @throws Exception
     */
    private static function createNewToken(string $userId, int $type, string $oldToken = null, string $fingerPrint = "", int $confirmationType = null, string $whatFor = "default", float|int $expirationDelay = -1, bool|null $singleTokenPerTime = null): ConfirmationToken|AuthToken
    {
        $datetime = new DateTime();
        if ($type == TokensTypes::AUTHENTICATION_BY_TOKEN || $type == TokensTypes::AUTHENTICATION_BY_COOKIE) {
            if ($expirationDelay == -1) {
                $delay = self::getAuthTokenExpirationDelay();
            }
            elseif ($expirationDelay > 0) {
                $delay = $expirationDelay;
            }
            else{
                throw new Exception("Expiration delay can't be < 0");
            }
            $datetime->modify('+'.$delay.' seconds');
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
            if ($expirationDelay == -1) {
                $delay = self::getConfirmationTokenExpirationDelay();
            }
            elseif ($expirationDelay > 0) {
                $delay = $expirationDelay;
            }
            else{
                throw new Exception("Expiration delay can't be < 0");
            }
            if ($singleTokenPerTime == null) $singleTokenPerTime = self::$config['ConfirmationToken']['singleTokenPerTime'];
            $datetime->modify('+'.$delay.' seconds');
            $encryptedUserId = call_user_func_array([new self::$UserIdEncrypter(), 'encrypt'], [$userId]);
            $token = ConfirmationToken::builder()
                ->withUserId($encryptedUserId)
                ->withType($confirmationType)
                ->withContent(call_user_func_array([new self::$ConfirmationCodeGenerator(), 'generate'], [$confirmationType]))
                ->withExpiredAt($datetime)
                ->withWhatFor($whatFor)
                ->build();
            if ($singleTokenPerTime) {
                $oldToken = ConfirmationTokenModel::where('userId', $userId)
                    ->where('whatFor', $whatFor)
                    ->where('type', $confirmationType)
                    ->where('expire_at', '>', date('Y-m-d H:i:s'))
                    ->get();
                if ($oldToken != null AND count($oldToken) != 0) {
                    $oldContent = $oldToken->last()->content;
                    $oldToken->each(function ($token) {
                        $token->delete();
                    });
                }
            }
            ConfirmationTokenModel::create([
                'userId' => $userId,
                'content' => $oldContent??$token->getContent(),
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
     * @param float|int $expirationDelay
     * @return AuthToken
     * @throws Exception
     */
    public static function createNewAuthToken(string $userId, string $fingerPrint = "", bool $usingCookies = false, float|int $expirationDelay = -1): AuthToken
    {
        return self::createNewToken(userId: $userId, type: $usingCookies ? TokensTypes::AUTHENTICATION_BY_COOKIE : TokensTypes::AUTHENTICATION_BY_TOKEN, fingerPrint: $fingerPrint, expirationDelay: $expirationDelay);
    }

    /**
     * @param string $userId
     * @param int $confirmationType
     * @param string $whatFor
     * @param float|int $expirationDelay
     * @param bool|null $singleTokenPerTime
     * @return ConfirmationToken
     * @throws Exception
     */
    public static function createNewConfirmationToken(string $userId, int $confirmationType = ConfirmationsTokenTypes::SMALL_CODE, string $whatFor = "default", float|int $expirationDelay = -1, bool|null $singleTokenPerTime = null): ConfirmationToken
    {
        return self::createNewToken(
            userId: $userId,
            type: TokensTypes::CONFIRMATION_CODE,
            confirmationType: $confirmationType,
            whatFor: $whatFor,
            expirationDelay: $expirationDelay,
            singleTokenPerTime: $singleTokenPerTime
        );
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
                        $authTokenResultBuilder->setTokenId(strval($authTokenModel->id??""));
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
     * @param bool $deleteAfterCheck
     * @return ConfirmationTokenResponse
     */
    public static function checkConfirmationCode(string $code, string $encryptedUserId = null, string $whatFor = "default", bool $deleteAfterCheck = true): ConfirmationTokenResponse
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
                        $confirmationTokenResultsBuilder->withWhatFor($whatFor);
                        $confirmationTokenResultsBuilder->setTokenId(strval($confirmationTokenModel->id??""));
                        if ($deleteAfterCheck) {
                            ConfirmationTokenModel::find($confirmationTokenModel->id)->delete();
                        }
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
     * @param bool $deleteAfterCheck
     * @return ConfirmationTokenResponse
     */
    public static function checkConfirmationUrl(string $url, string $whatFor = "default", bool $deleteAfterCheck = true): ConfirmationTokenResponse
    {
        /** @var UserIdAndToken $userIdAndToken */
        $userIdAndToken = call_user_func_array([new TokensValidation::$ConfirmationUrlBuilder(), 'getUserIdAndTokenFromUrl'], [$url]);
        if ($userIdAndToken != null) {
            return self::checkConfirmationCode($userIdAndToken->getToken(), $userIdAndToken->getUserId(), $whatFor, $deleteAfterCheck);
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
     * @param bool $deleteAfterCheck
     * @return ConfirmationTokenResponse
     */
    public static function checkConfirmationUrlParamsFromGET(array $_GET_ARRAY, string $whatFor = "default", bool $deleteAfterCheck = true): ConfirmationTokenResponse
    {
        /** @var UserIdAndToken $userIdAndToken */
        $userIdAndToken = call_user_func_array([new TokensValidation::$ConfirmationUrlBuilder(), 'getUserIdAndTokenFromGET'], [$_GET_ARRAY]);
        if ($userIdAndToken != null) {
            return self::checkConfirmationCode($userIdAndToken->getToken(), $userIdAndToken->getUserId(), $whatFor, $deleteAfterCheck);
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

    /**
     * @param string $userId
     * @param string $target_email
     * @param float|int $expirationDelay
     * @param string $whatFor
     * @param string $data
     * @return Invitation
     * @throws Exception
     */
    public static function createInvitation(string $userId, string $target_email, float|int $expirationDelay = -1, string $whatFor = "default", string $data = ""): Invitation
    {
        $datetime = new DateTime();
        if ($expirationDelay == -1) {
            $delay = self::getInvitationTokenExpirationDelay();
        }
        elseif ($expirationDelay > 0) {
            $delay = $expirationDelay;
        }
        else{
            throw new Exception("Expiration delay can't be < 0");
        }
        $datetime->modify('+'.$delay.' seconds');
        $invitation = Invitation::builder($userId)
            ->withTargetEmail($target_email)
            ->withContent(call_user_func_array([new self::$InvitationTokenGenerator(), 'generate'], []))
            ->withExpireAt($datetime)
            ->withWhatFor($whatFor)
            ->withData($data)
            ->build();

        InvitationModel::upsert(
            [
                'userId' => $invitation->getUserId(),
                'content' => $invitation->getContent(),
                'target_email' => $invitation->getTargetEmail(),
                'expire_at' => $invitation->getExpireAt(),
                'whatFor' => $invitation->getWhatFor(),
                'data' => $invitation->getData()
            ],
            ['whatFor', 'target_email']
        );

        return $invitation;
    }

    /**
     * @param string $token
     * @param string $whatFor
     * @param bool $thenAccept
     * @return InvitationResponse
     */
    public static function checkInvitationToken(string $token, string $whatFor = "default", bool $thenAccept = false): InvitationResponse
    {
        $invitationResultsBuilder = InvitationResponse::builder()
            ->setValidationSucceed(false);
        if ($token != null) {
            $modelBuilder = InvitationModel::where('content', '=', $token)->where('accepted', '=', 0);
            $invitationModel = $modelBuilder->get();
            if ($invitationModel != null AND count($invitationModel) != 0) {
                $invitationModel = $invitationModel[0];
                $invitationResultsBuilder->withUserId($invitationModel->userId);
                if (!self::isExpired(DateTime::createFromFormat('Y-m-d H:i:s', $invitationModel->expire_at))) {
                    if ($invitationModel->whatFor == $whatFor || $whatFor == "default") {
                        $invitationResultsBuilder->setTokenId(strval($invitationModel->id??""));
                        $invitationResultsBuilder->setValidationSucceed(true);
                        $invitationResultsBuilder->withData($invitationModel->data);
                        $invitationResultsBuilder->withTargetEmail($invitationModel->target_email);
                        if ($thenAccept) {
                            $invitationModel->update([
                                "accepted" => 1
                            ]);
                        }
                        return $invitationResultsBuilder->build();
                    }
                    else{
                        $invitationResultsBuilder->setCause("REASON_INVALID");
                    }
                }
                else{
                    $invitationResultsBuilder->setCause("TOKEN_EXPIRED");
                }
                $invitationModel->delete();
            }
            else{
                $invitationResultsBuilder->setCause("TOKEN_INVALID");
            }
        }
        else{
            $invitationResultsBuilder->setCause("NO_TOKEN_PROVIDED");
        }
        return $invitationResultsBuilder->build();
    }

    /**
     * @param string $url
     * @param string $whatFor
     * @param bool $thenAccept
     * @return InvitationResponse
     */
    public static function checkInvitationUrl(string $url, string $whatFor = "default", bool $thenAccept = false): InvitationResponse
    {
        return self::checkInvitationToken(call_user_func_array([new TokensValidation::$InvitationUrlBuilder(), 'getTokenFromUrl'], [$url]), $whatFor, $thenAccept);
    }

    /**
     * @param array $_GET_ARRAY
     * @param string $whatFor
     * @param bool $thenAccept
     * @return InvitationResponse
     */
    public static function checkInvitationUrlParamsFromGET(array $_GET_ARRAY, string $whatFor = "default", bool $thenAccept = false): InvitationResponse
    {
        return self::checkInvitationToken(call_user_func_array([new TokensValidation::$InvitationUrlBuilder(), 'getTokenFromGET'], [$_GET_ARRAY]), $whatFor, $thenAccept);
    }

    /**
     * @param Request $request
     * @param string $whatFor
     * @param bool $thenAccept
     * @return InvitationResponse
     */
    public static function checkInvitationRequest(Request $request, string $whatFor = "default", bool $thenAccept = false): InvitationResponse
    {
        return self::checkInvitationToken(call_user_func_array([new TokensValidation::$InvitationUrlBuilder(), 'getTokenFromRequest'], [$request]), $whatFor, $thenAccept);
    }
}