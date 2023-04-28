<?php

use HichemtabTech\TokensValidation\Actions\Authentication\AuthTokenCookiesHandler;
use HichemtabTech\TokensValidation\Actions\Authentication\AuthTokenGenerator;
use HichemtabTech\TokensValidation\Actions\Confirmation\ConfirmationCodeGenerator;
use HichemtabTech\TokensValidation\Actions\Confirmation\ConfirmationUrlBuilder;
use HichemtabTech\TokensValidation\Actions\Invitation\InvitationTokenGenerator;
use HichemtabTech\TokensValidation\Actions\Invitation\InvitationUrlBuilder;
use HichemtabTech\TokensValidation\Actions\UserIdEncrypter;

return [
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'db',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ],
    ],

    'AuthTokens' => [
        'expirationDelay' => 60*60*24*7,
        'AuthTokenGenerator' => AuthTokenGenerator::class,
        'AuthTokenCookiesHandler' => AuthTokenCookiesHandler::class,
    ],

    'ConfirmationToken' => [
        'expirationDelay' => 60*10,
        'ConfirmationUrlBuilder' => ConfirmationUrlBuilder::class,
        'ConfirmationCodeGenerator' => ConfirmationCodeGenerator::class,
        'UserIdEncrypter' => UserIdEncrypter::class
    ],


    'InvitationToken' => [
        'expirationDelay' => 60*60*24*3,
        'InvitationUrlBuilder' => InvitationUrlBuilder::class,
        'InvitationTokenGenerator' => InvitationTokenGenerator::class,
        'InvitationBaseUrl' => "http://localhost/invitations",
    ],

    'features' => [
        'AuthTokens',
        'ConfirmationToken',
        //'InvitationsTokens'
    ]
];