<?php

namespace HichemtabTech\TokensValidation\Model\authentication;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * AuthTokenModel
 * @mixin Builder
 * @property DateTime $expire_at
 * @property string $userId
 * @property mixed $type
 */
class AuthTokenModel extends Model
{
    protected $table = "auth_tokens";
    protected $fillable = ['userId', 'content', 'type', 'expire_at', 'userAgent', 'fingerprint'];
}