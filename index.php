<?php

use HichemtabTech\TokensValidation\Model\confirmation\ConfirmationsTokenTypes;
use HichemtabTech\TokensValidation\TokensValidation;

require 'vendor/autoload.php';

TokensValidation::setDBHOST("localhost");
TokensValidation::setDBNAME("test");
TokensValidation::setDBUSER("root");
TokensValidation::setDBPASS("");
TokensValidation::prepare();


TokensValidation::setAuthTokenExpirationDelay(60*60*24*7);
TokensValidation::setConfirmationTokenExpirationDelay(60*10);
echo "<pre>";
try {
    $confirmationToken = TokensValidation::createNewConfirmationToken(
        userId: "1002",
        whatFor: "email-confirmation"
    );

    echo $confirmationToken->getContent();
    echo $confirmationToken->getUrl("http://localhost/email-check");

} catch (Exception $e) {
    print_r($e);
    die();
}
print_r([
    $confirmationToken->getContent(),
    $confirmationToken->getUrl("http://localhost/email-check")
]);