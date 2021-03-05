<?php

namespace Juampi92\LaravelQueryCache\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Cache;
use Juampi92\LaravelQueryCache\LaravelQueryCacheServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->clearCache();

        $this->loadMigrationsFrom(__DIR__.'/stubs/database/migrations');
        $this->artisan('migrate', ['--database' => 'sqlite']);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Juampi92\\LaravelQueryCache\\Tests\\stubs\\database\\factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelQueryCacheServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set(
            'cache.driver',
            getenv('CACHE_DRIVER') ?: env('CACHE_DRIVER', 'array')
        );
    }

    /**
     * Clear the cache.
     *
     * @return void
     */
    protected function clearCache()
    {
        Cache::flush();
    }
}
