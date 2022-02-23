# Laravel Multitenancy Impersonate

Laravel multitenancy impersonation from landlord to tenant.

This package is made to be used with [Spatie Laravel Multitenancy](https://github.com/spatie/laravel-multitenancy).

## Installation

You can install the package via composer:

```bash
composer require elrod/laravel-multitenancy-impersonate
```
## Publish Config and Migrations
```bash
php artisan vendor:publish
```
You will see list of things to publish:
![Image text](https://drive.google.com/uc?export=download&id=1MZjiwRRu2cvgwwc9F1RzBlUknQmh2yyM)
Select what you want to post by giving the package index number
## Usage

### Landlord Controller
The Landlord controller creates the token and redirects to the tenant for automatic login.

The redirectTenant method can be used after creating a tenant
``` php

use elrod\MultitenancyImpersonate\Traits\CanImpersonate;

class ImpersonateController
{
    use CanImpersonate;

    public function redirectTenant($id)
    {
        $tenant = Tenant::find($id);
        
        $redirect_url = "http://{$tenant->domain}/admin";

        $token = $this->createToken($tenant,auth()->user(),$redirect_url);

        $this->impersonate($tenant,$token->token,auth()->user());
            
        $tenant_url = "http://{$tenant->domain}/admin/impersonate";

        return redirect("{$tenant_url}/{$token->token}");
    }

}
```

Create the routes

``` php
Route::get('/admin/impersonate/{token}', function ($token) {

    $impersonate = ImpersonateToken::where('token',$token)->first();

    $user = User::find($impersonate->user_id);

    Auth::login($user);

    return redirect()->route('admin');

});

Route::middleware(['auth:sanctum', 'verified'])->get('/admin', function () {
    return 'Hello World';
})->name('admin');
```

## Credits

- [Victor Yoalli](https://github.com/victoryoalli)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
