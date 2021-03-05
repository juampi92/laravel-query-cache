# Laravel Query Cache

[![Latest Version on Packagist](https://img.shields.io/packagist/v/juampi92/laravel-query-cache.svg?style=flat-square)](https://packagist.org/packages/juampi92/laravel-query-cache)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/juampi92/laravel-query-cache/run-tests?label=tests)](https://github.com/juampi92/laravel-query-cache/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/juampi92/laravel-query-cache/Check%20&%20fix%20styling?label=code%20style)](https://github.com/juampi92/laravel-query-cache/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/juampi92/laravel-query-cache.svg?style=flat-square)](https://packagist.org/packages/juampi92/laravel-query-cache)

This package provides a set of macros to cache your Laravel Queries just like Cache::remember.

```php
$featuredPost = Post::published()->orderByMostViews()
    ->cacheDay('post:featured') // <- Here
    ->first();
```

## Installation

You can install the package via composer:

```bash
composer require juampi92/laravel-query-cache
```

That's it! No config or Trait necessary. The package auto-discovery will boot the macros.

## Usage

Instead of doing:

```php
Cache::remember('post:count', $ttl, () => Post::published()->count());
```

You now do:

```php
Post::published()->cache('post:count', $ttl)->count();
```

You can use it in Eloquent Queries as well as in normal Queries.

```php
DB::table('posts')
    ->whereNotNull('published_at')
    ->latest()
    ->cacheHour('post:latest')
    ->first();

Posts::published()
    ->cacheHour('post:count')
    ->count();
```

List of macros:

```php
Post::cache('cache:key', $ttl)->get();
Post::cacheMinute('cache:key')->first();
Post::cacheHour('cache:key')->pluck('id');
Post::cacheDay("cache:key:$id")->find($id);
Post::cacheWeek('cache:key:paginate:10')->paginate(10);
Post::cacheForever('cache:key')->count();
```

## Advanced usage

### Tags

```php
Post::query()
    ->where(...)
    ->cache('post:count')->tags(['posts'])
    ->count();
```

### Different store

```php
Post::query()
    ->where(...)
    ->cache('post:count')->store('redis')
    ->count();
```

### Add your custom cache duration

This is maybe more advanced, but you can do so by [opting out of discovery](https://laravel.com/docs/8.x/packages#opting-out-of-package-discovery), and then importing it yourself:

```php
use Juampi92\LaravelQueryCache\LaravelQueryCacheServiceProvider as BaseServiceProvider;

class LaravelQueryCacheServiceProvider extends BaseServiceProvider
{
    protected function getCustomTimes(): array
    {
        return array_merge(
            parent::getCustomTimes(),
            [
                'rememberForever' => null,
                'cacheFifteenMinutes' => 60 * 15,
            ]
        );
    }
}
```

The original method has
```php
[
    'cacheForever' => null,
    'cacheMinute' => 60,
    'cacheHour' => 60 * 60,
    'cacheDay' => 60 * 60 * 24,
    'cacheWeek' => 60 * 60 * 24 * 7,
]
```

## Disclaimer

This package is supposed to be a nice integration of Cache remember inside the Query Builder.
If you're looking for a advanced eloquent specific cache, I recommend to check out [laravel-eloquent-query-cache](https://github.com/renoki-co/laravel-eloquent-query-cache).

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
