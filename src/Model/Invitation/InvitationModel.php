<?php

namespace HichemtabTech\TokensValidation\Model\Invitation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * InvitationModel
 * @mixin Builder
 * @method static insertOrReplace(array $array)
 */
class InvitationModel extends Model
{

    protected $table = "invitation_tokens";

    protected $casts = [
        'expire_at' => 'datetime',
    ];
}
