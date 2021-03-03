# Provide easy interface for caching laravel queries

[![Latest Version on Packagist](https://img.shields.io/packagist/v/juampi92/laravel-query-cache.svg?style=flat-square)](https://packagist.org/packages/juampi92/laravel-query-cache)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/juampi92/laravel-query-cache/run-tests?label=tests)](https://github.com/juampi92/laravel-query-cache/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/juampi92/laravel-query-cache/Check%20&%20fix%20styling?label=code%20style)](https://github.com/juampi92/laravel-query-cache/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/juampi92/laravel-query-cache.svg?style=flat-square)](https://packagist.org/packages/juampi92/laravel-query-cache)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require juampi92/laravel-query-cache
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="juampi92\LaravelQueryCache\LaravelQueryCacheServiceProvider" --tag="laravel-query-cache-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Juampi92\LaravelQueryCache\LaravelQueryCacheServiceProvider" --tag="laravel-query-cache-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$laravel-query-cache = new Juampi92\LaravelQueryCache();
echo $laravel-query-cache->echoPhrase('Hello, Juampi92!');
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

- [Juampi92](https://github.com/juampi92)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
