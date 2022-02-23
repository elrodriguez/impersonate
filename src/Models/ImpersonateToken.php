<?php

namespace Elrod\MultitenancyImpersonate\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class ImpersonateToken extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'token',
        'impersonator_id',
        'user_id',
        'redirect_url',
        'expired_at',
        'auth_guard',
        'impersonated_at',
        'ip_address'
    ];

}
