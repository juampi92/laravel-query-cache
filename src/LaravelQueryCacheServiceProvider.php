<?php

namespace Juampi92\LaravelQueryCache;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\ServiceProvider;

class LaravelQueryCacheServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerBaseMacro();

        foreach ($this->getCustomTimes() as $macroName => $ttl) {
            $this->registerCustomMacro($macroName, $ttl);
        }
    }

    protected function getCustomTimes(): array
    {
        return [
            'cacheMinute' => 60,
            'cacheHour' => 60 * 60,
            'cacheDay' => 60 * 60 * 24,
            'cacheWeek' => 60 * 60 * 24 * 7,
        ];
    }

    private function registerBaseMacro(): void
    {
        QueryBuilder::macro('cache', function ($name, $ttl = null) {
            /** @var QueryBuilder $this */
            return new CacheHighOrderFunction($name, $ttl, $this);
        });
        EloquentBuilder::macro('cache', function ($name, $ttl = null) {
            /** @var EloquentBuilder $this */
            return new CacheHighOrderFunction($name, $ttl, $this);
        });
    }

    /**
     * Will register every custom
     * @param  string  $macroName
     * @param $ttl
     */
    private function registerCustomMacro(string $macroName, $ttl = null): void
    {
        QueryBuilder::macro($macroName, function ($name) use ($ttl) {
            /** @var QueryBuilder $this */
            return new CacheHighOrderFunction($name, $ttl, $this);
        });
        EloquentBuilder::macro($macroName, function ($name) use ($ttl) {
            /** @var EloquentBuilder $this */
            return new CacheHighOrderFunction($name, $ttl, $this);
        });
    }
}
