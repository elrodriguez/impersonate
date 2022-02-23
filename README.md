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
``` php

use elrod\MultitenancyImpersonate\Traits\CanImpersonate;

class ImpersonateController
{
    use CanImpersonate;

    public function store(Request $request)
    {
        $tenant = Tenant::find($request->get('tenant_id'));
        $redirect_url = "https{$tenant->domain}/admin";
        $impersonate = $this->impersonate($tenant,auth()->user(),$redirect_url);

        $tenant_url = "https{$tenant->domain}/admin/impersonate";

        return redirect("{$tenant_url}/{$impersonate->token}");
    }

}
```

### Impersonate Tenant Controller
Impersonates to the user of your choice. Needs a valid token and the user to be impersonated.
Will be redirected to the provided `$redirect_url`.
```php
use CanImpersonate;

public function __invoke(Request $request, string $token)
    {
        $user = User::firstOrFail();

        return $this->impersonate($token, $user);
    }
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email victoryoalli@gmail.com instead of using the issue tracker.

## Credits

- [Victor Yoalli](https://github.com/victoryoalli)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
