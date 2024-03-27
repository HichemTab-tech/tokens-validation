<?php

namespace HichemtabTech\TokensValidation\Actions\Confirmation;

use HichemtabTech\TokensValidation\Model\Confirmation\ConfirmationsTokenTypes;
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
        switch ($confirmationType) {
            case ConfirmationsTokenTypes::SMALL_CODE:
                return TokensValidation::generateAlphaNumKey(6, true);
            case ConfirmationsTokenTypes::IN_URL:
                return TokensValidation::generateAlphaNumKey(15);
            default:
                return "";
        }
    }
}