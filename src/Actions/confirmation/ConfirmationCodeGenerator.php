<?php

namespace HichemtabTech\TokensValidation\Actions\confirmation;

use HichemtabTech\TokensValidation\Model\confirmation\ConfirmationsTokenTypes;
use HichemtabTech\TokensValidation\TokensValidation;

/**
 *
 */
class ConfirmationCodeGenerator
{


    /**
     * @param int $confirmationType
     * @return string
     */
    public function generate(int $confirmationType): string
    {
        return match ($confirmationType) {
            ConfirmationsTokenTypes::SMALL_CODE => TokensValidation::generateAlphaNumKey(6, true),
            ConfirmationsTokenTypes::IN_URL => TokensValidation::generateAlphaNumKey(15),
            default => "",
        };
    }
}