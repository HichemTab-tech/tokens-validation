<?php

namespace HichemtabTech\TokensValidation\Model\Confirmation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * ConfirmationTokenModel
 * @mixin Builder
 */
class ConfirmationTokenModel extends Model
{
    protected $table = "confirmation_tokens";
    protected $fillable = ['userId', 'content', 'type', 'expire_at', 'whatFor'];
}