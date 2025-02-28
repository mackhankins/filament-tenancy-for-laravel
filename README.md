# Provides integration of FilementPhp with Tenancy for Laravel package for SaaSykit Tenancy

[![Latest Version on Packagist](https://img.shields.io/packagist/v/saasykit/filament-tenancy-for-laravel.svg?style=flat-square)](https://packagist.org/packages/saasykit/filament-tenancy-for-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/saasykit/filament-tenancy-for-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/saasykit/filament-tenancy-for-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/saasykit/filament-tenancy-for-laravel/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/saasykit/filament-tenancy-for-laravel/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/saasykit/filament-tenancy-for-laravel.svg?style=flat-square)](https://packagist.org/packages/saasykit/filament-tenancy-for-laravel)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require saasykit/filament-tenancy-for-laravel
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-tenancy-for-laravel-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-tenancy-for-laravel-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-tenancy-for-laravel-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentTenancyForLaravel = new Saasykit\FilamentTenancyForLaravel();
echo $filamentTenancyForLaravel->echoPhrase('Hello, Saasykit!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [SaaSykit](https://github.com/saasykit)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
