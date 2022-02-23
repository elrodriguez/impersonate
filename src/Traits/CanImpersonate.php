<?php

namespace Elrod\MultitenancyImpersonate\Traits;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Models\Tenant;
use Elrod\MultitenancyImpersonate\Models\ImpersonateToken;

/**
 *
 */
trait CanImpersonate
{
    public function createToken(Tenant $tenant, Authenticatable $user, $redirect_url = null, $auth_guard = null)
    {
        $tenant->makeCurrent();

        $redirect_url = $redirect_url ?? "http://{$tenant->domain}".config('multitenancy-impersonate.redirect_path', '/admin');
        $auth_guard = $auth_guard ?? config('multitenancy-impersonate.auth_guard', 'web');

        $token = ImpersonateToken::create([
            'token' => Str::uuid(),
            'impersonator_id' => $user->id,
            'redirect_url' => $redirect_url,
            'expired_at' => now()->addSeconds(config('multitenancy-impersonate.ttl', 1)),
            'auth_guard' => $auth_guard
        ]);
        
        $tenant->forgetCurrent();

        return $token;
    }

    public function impersonate(Tenant $tenant,string $token, Authenticatable $user)
    {
        $tenant->makeCurrent();
        $impersonate = ImpersonateToken::where('expired_at', '>', now())
            ->whereNull('impersonated_at')
            ->where('token',$token)
            ->firstOrFail();

        auth($impersonate->auth_guard)->login($user);

        $impersonate->update([
            'impersonated_at'   => now(),
            'ip_address'        => request()->ip(),
            'user_id'           => auth()->id()
        ]);

        return redirect($impersonate->redirect_url, 301);
    }
}
