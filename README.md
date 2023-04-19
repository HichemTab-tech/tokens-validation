
![Logo](https://hichemtech.website/assets/img/logo.ico)


# TokensValidation

TokensValidation is a PHP library designed to generate and verify authentication and confirmation tokens dynamically. It is ideal for web applications and software that require secure user authentication and authorization. The library generates authentication and confirmation tokens that can be used to log in users, reset passwords, and confirm email addresses.


## Installation

The TokensValidation library can be installed via Composer by running the following command:

```bash
  composer require hichemtab-tech/tokens-validation
```


## Features

- Authenticate the user after browser closed without password
- Generate custom confirmations codes with expiration delay.
- Flexibility of usage
- Security & Encryption


## Usage


To use the library, you first need to set up the database connection parameters by calling the following methods:

```PHP
<?php

use HichemtabTech\TokensValidation\TokensValidation;

require 'vendor/autoload.php';

TokensValidation::setDBHOST("localhost");
TokensValidation::setDBNAME("test");
TokensValidation::setDBUSER("root");
TokensValidation::setDBPASS("");
```
After that, you can call the prepare() method to create the necessary tables in the database:

```PHP
TokensValidation::prepare();
```
It is perfectly fine for the user to call this code repeatedly because the library will perform the necessary preparation steps, such as creating tables, only once. After that, these steps will be ignored automatically, unless the tables are no longer present.

## Authentication Tokens
Authentication tokens are used to authenticate a user after they have logged in. TokensValidation can generate and verify authentication tokens.

TokensValidation generates a security token to identify requests made by the user when they log into the system. This token also enables the user to log in without having to enter their password again, even after closing the browser, by utilizing cookies.

The library provides three ways to handle the authentication tokens:
* Using cookies
* Handling the token yourself
* Overriding the cookies handler methods

Here are some examples of using TokensValidation to handle authentication tokens:

#### 1-Using Cookies
First example is an Auto generating and verifing:
- To generate an authentication token using cookies, call the following method:
```PHP
$authToken = TokensValidation::createNewAuthToken(
        userId: $uid,
        usingCookies: true
    );

// you can print $authToken for additionnal informations about the created token.
```
This method generates a new authentication token for the given user ID and saves it in a cookie. The second argument specifies whether to use cookies or not.

To check the authentication token, call the following method:
```PHP
$result = TokensValidation::checkAuthToken();
    if ($result->isValidationSucceed()) {
        // log in the user automatically
        //for example :
        autoLogin($result->getUserId());
    }
    else{
        //throw an error or redirect to log in
    }

```

**TokensValidation::checkAuthToken()** checks the authentication token in the cookie and returns the result. If the validation is successful, a new token is generated and replaced in the cookie.

##### check the token without regenerate a new one:

```PHP
$result = TokensValidation::checkAuthTokenWithoutRegenerate();
```
This line of code executes a function called TokensValidation::checkAuthTokenWithoutRegenerate() which performs a check on the token to ensure that it is valid, but it does not regenerate a new token to replace the old one. The purpose of this check is to verify that the token is still valid and has not been tampered with. If the token is still valid, the function will return a successful result, which will be stored in the variable "$result".

It is important to note that this function only checks the token's validity without regenerating it. If the token is invalid or has expired, the function will return an error or failure message, indicating that the user must log in again to obtain a new token. Therefore, this function provides an additional layer of security by ensuring that the token is still valid before proceeding with any further actions that require it.

###

#### 2-Handling the token yourself

To generate an authentication token and handle it yourself, call the following method:

```PHP
$authToken = TokensValidation::createNewAuthToken(
        userId: $uid,
        usingCookies: false
    );

    echo $authToken->getUserId();
    echo $authToken->getContent();
```

This method generates a new authentication token for the given user ID and returns the token object without saving the token anywhere to let you handle it as you like.

To check the authentication token, call the following method and pass the token as argument:

```PHP
$result = TokensValidation::checkAuthToken(authToken: $authToken);
    if ($result->isValidationSucceed()) {
        // log in the user automatically
        //for example :
        echo $result->getUserId();
        echo $result->getNewToken()->getContent();// save it in cookies or whatever you want
        autoLogin($result->getUserId());
    }
    else{
        //throw an error or redirect to log in
    }
```

###

#### 3-Overriding the cookies handler methods:

To override the cookies handler methods, create a class that extends the **AuthTokenCookiesHandler** class and implements the **save()**, **get()**, and **delete()** methods. Then, set the **$AuthTokenCookiesHandler** property to the name of your new class. Here's an example:

```PHP
use HichemtabTech\TokensValidation\Actions\authentication\AuthTokenCookiesHandler;

class MyAuthTokenCookiesHandler extends AuthTokenCookiesHandler
{
    public function save(AuthToken $authToken): void
    {
        //save the $authToken in cookies or what ever you want
    }

    public function get(): ?string
    {
        //get it
    }

    public function delete(): void
    {
        //delete it
    }
}

TokensValidation::$AuthTokenCookiesHandler = MyAuthTokenCookiesHandler::class;

// this should be called after preparation

```


## Fingerprint
You can add an extra layer of security to authentication tokens by using a browser fingerprint. The library supports fingerprinting by passing a fingerprint string to the createNewAuthToken() method.

```PHP
$authToken = TokensValidation::createNewAuthToken(
        userId: $uid,
        fingerPrint: $somefingerprint
    );
```
To check the authentication token with a fingerprint, call the **checkAuthToken()** method with the fingerprint argument.

```PHP
$result = TokensValidation::checkAuthToken(authToken: $authToken, fingerPrint: $somefingerprint);
```

- *to generate the fingerprint you can use for example https://github.com/Valve/fingerprintjs2*



### Confirmation tokens

Confirmation tokens are unique codes that can be generated by **TokensValidation** to verify the identity of a user or to confirm their authorization for a specific action. In general, confirmation tokens are used for email verification or password reset purposes, although they can be used for other purposes as well.

Confirmation processes can be classified into two types: those that require the user to click on a confirmation link *(by URL)* and those that require the user to manually enter a confirmation code *(by typing)*.

#### 1- By Url
In this case, the library generates a lengthy token that includes an encrypted user ID and is designed to be included in URL parameters.

```PHP
$confirmationToken = TokensValidation::createNewConfirmationToken(
        userId: $uid,
        confirmationType: ConfirmationsTokenTypes::IN_URL
    );

    echo $confirmationToken->getContent();// if want to view the long confirmation token
    echo $confirmationToken->getUrl("http://localhost/email-check");// to get the full prepared url with the base url http://localhost/email-check

    //you will get the url like this:
    //http://localhost/email-check?u=def5020080134ecc82ee1c1c2536ccdb0ec3c50161a9b6ab6f0f24c34730b73174327d2990ef8f583cec5f86ba7c3d44c5a5c0adae17313d09d5479fbe83c33e91f00d3902699507fc16266931be4e0f90382e4614aba6d8&c=nkyZDxMqbnS2oPs
```

You can utilize these lines of code to verify the contents of a URL.

```PHP
$result = TokensValidation::checkConfirmationUrl(url: $url);
    if ($result->isValidationSucceed()) {
        //for example :
        echo $result->getUserId();
        //continue the request
    }
    else{
        //throw an error
    }
```

Or you can can inject **$_GET** directly:

```PHP
$result = TokensValidation::checkConfirmationUrlParamsFromGET(_GET_ARRAY: $_GET);
```

To override the ConfirmationUrl builder methods, create a class that extends the **ConfirmationUrlBuilder** class and implements the **getUrl(ConfirmationToken $confirmationToken, string $baseUrl)**, **getUserIdAndTokenFromUrl(string $url)**, and **getUserIdAndTokenFromGET(array $_GET_ARRAY)** methods. Then, set the $ConfirmationUrlBuilder property to the name of your new class. Here's an example:

```PHP
use HichemtabTech\TokensValidation\Actions\confirmation\ConfirmationUrlBuilder;
use HichemtabTech\TokensValidation\Actions\confirmation\UserIdAndToken;
use HichemtabTech\TokensValidation\Model\confirmation\ConfirmationToken;
use Purl\Url;

class MyConfirmationUrlBuilder extends ConfirmationUrlBuilder
{
    public function getUrl(ConfirmationToken $confirmationToken, string $baseUrl): string
    {
        $url = new Url($baseUrl);
        $url->query->set("uid", $confirmationToken->getUserId());// for userId
        $url->query->set("token", $confirmationToken->getContent());// for Code
        return $url->getUrl();
    }

    public function getUserIdAndTokenFromUrl(string $url): UserIdAndToken
    {
        $url = new Url($url);
        return UserIdAndToken::builder()
            ->setUserId($url->query->get("uid"))
            ->setToken($url->query->get("token"))
            ->build();
    }

    public function getUserIdAndTokenFromGET(array $_GET_ARRAY): UserIdAndToken
    {
        return UserIdAndToken::builder()
            ->setUserId($_GET_ARRAY["uid"]??"")
            ->setToken($_GET_ARRAY["token"]??"")
            ->build();
    }
}
```
#### 2- By typing

In this case, the user needs to enter the confirmation token generated by the library into a form field. The library can generate a short confirmation token for convenience:

```PHP
$confirmationToken = TokensValidation::createNewConfirmationToken(
        userId: $uid,
        confirmationType: ConfirmationsTokenTypes::SMALL_CODE
    );

    echo $confirmationToken->getContent();


    $result = TokensValidation::checkConfirmationCode(code: $token);
    if ($result->isValidationSucceed()) {
        //for example :
        echo $result->getUserId();
        //continue the request
    }
    else{
        //throw an error
    }
```

#### WhatFor field:
To ensure that each confirmation code is used for its intended purpose, you can include a "**whatFor**" parameter when calling the **createNewConfirmationToken** function. This parameter allows you to specify the purpose of the confirmation code, providing an additional layer of security and accuracy.

```PHP
$confirmationToken = TokensValidation::createNewConfirmationToken(
        userId: $uid,
        confirmationType: ConfirmationsTokenTypes::SMALL_CODE,
        whatFor: "email-confirmation"
    );
```


To check it :
```PHP
$result = TokensValidation::checkConfirmationCode(code: $token, whatFor: "email-confirmation");
```

If the "whatFor" parameter does not match the intended purpose of the confirmation code, the validation process will fail.

### Tokens generator

You can modify the behavior of the token and confirmation code generator by creating a new class that extends the **AuthTokenGenerator::class** and **ConfirmationCodeGenerator::class**. This allows you to customize the functionality of the generator to meet the specific needs of your project.

```PHP
TokensValidation::$AuthTokenGenerator = MyAuthTokenGenerator::class;
TokensValidation::$ConfirmationCodeGenerator = MyConfirmationCodeGenerator::class;
```

### Token expiration

By default, tokens generated by the library expire after a period of time (7 days for authentication tokens and 10 minutes for confirmation tokens). You can customize these expiration periods using the **setAuthTokenExpiration()** and **setConfirmationTokenExpiration()** methods, respectively:

```PHP
// Set authentication token expiration period to 2 days
TokensValidation::setAuthTokenExpirationDelay(2 * 24 * 60 * 60);  // seconds

// Set confirmation token expiration period to 1 hour
TokensValidation::setConfirmationTokenExpirationDelay(60 * 60);  // seconds

//these lines should be called after preparation.
```

### Identificating errors

To identify and troubleshoot errors that may occur, you can utilize the **$result->getCause()** function, which returns a reference code to indicate the specific cause of the error. Several possible error codes may be returned by this function.
## Color Reference

| Error             | Cause                                                                |
| ----------------- | ------------------------------------------------------------------ |
| "EXCEPTION" | Means there was an exception during the execution you can check the exception by calling **$result->getException()** |
| "NO_TOKEN_PROVIDED" | When the token parameters is null |
| "TOKEN_INVALID" | When no matching token is found in the database. |
| "TOKEN_EXPIRED" | Expired token |
| "REASON_INVALID" | When attempting to verify a confirmation token and the "whatFor" field does not match the intended purpose of the token. |
| "TOKEN_UID_INVALID" | When the confirmation URL contains an invalid or incorrect user ID. |
| "FINGERPRINT_INVALID" | When the fingerprint field of the authentication token does not match the expected value. |


## License

[MIT](https://choosealicense.com/licenses/mit/)

