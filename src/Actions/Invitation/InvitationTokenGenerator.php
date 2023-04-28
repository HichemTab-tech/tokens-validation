<?php

namespace HichemtabTech\TokensValidation\Actions\Invitation;

use HichemtabTech\TokensValidation\TokensValidation;

class InvitationTokenGenerator
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return TokensValidation::generateAlphaNumKey(25);
    }
}