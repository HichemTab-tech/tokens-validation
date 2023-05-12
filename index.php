<?php

use HichemtabTech\TokensValidation\TokensValidation;

require 'vendor/autoload.php';

TokensValidation::setConfig(include 'src/configtest.php');
TokensValidation::setFeatures([
    'AuthTokens',
    'ConfirmationToken',
]);

TokensValidation::setInvitationTokenExpirationDelay(60*60*24);
TokensValidation::$InvitationBaseUrl = "http://localhost/invitations";
/*TokensValidation::$InvitationTokenGenerator = MyInvitationTokenGenerator::class;
TokensValidation::$InvitationUrlBuilder = MyInvitationUrlBuilder::class;*/

TokensValidation::prepare();

/*echo "<pre>";
print_r(TokensValidation::$config);
die();*/
/*try {
    $inv = TokensValidation::createInvitation(
        userId: "1002",
        target_email: "user@example.com",
        whatFor: "administration",
    );


} catch (Exception $e) {
    print_r($e);
    die();
}
print_r([
    $inv->getUrl(),
    $inv
]);*/
/*try {
    $inv = TokensValidation::checkInvitationUrl(
        url: "http://localhost/invitations?c=e6dXr7kg5aPO4Olz5RlpXHiww",
        whatFor: "administration",
    );


} catch (Exception $e) {
    print_r($e);
    die();
}
print_r([
    $inv
]);*/

//verify the data entered by the user.


/*$invitation = TokensValidation::checkInvitationToken(
    token: $_GET['token'],
    whatFor: "administration",
    thenAccept: true
);

if (!$invitation->isValidationSucceed()) {
    die("INVITATION_INVALID");
}*/

//insert the data entered by the user.
//performe some actions
echo "invitation accepted";

die();

