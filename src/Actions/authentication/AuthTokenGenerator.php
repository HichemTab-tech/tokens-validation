<?php

namespace HichemtabTech\TokensValidation\Actions\authentication;

use HichemtabTech\TokensValidation\TokensValidation;

class AuthTokenGenerator
{
    /**
     * @param string $userId
     * @return string
     */
    public function generate(string $userId): string
    {
        $maxLength = 25;
        $n1 = mt_rand(5, $maxLength);
        $n2 = $maxLength - $n1;
        return TokensValidation::generateAlphaNumKey($n1) . $userId . TokensValidation::generateAlphaNumKey($n2);
    }
}