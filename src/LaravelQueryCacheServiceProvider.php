<?php

namespace Juampi92\LaravelQueryCache;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\ServiceProvider;
use Juampi92\LaravelQueryCache\Macros\CacheMacro;

class LaravelQueryCacheServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerBaseMacro();

        foreach ($this->getCustomTimes() as $macroName => $ttl) {
            $this->registerCustomMacro($macroName, $ttl);
        }
    }

    protected function getCustomTimes(): array
    {
        return [
            'cacheForever' => null,
            'cacheMinute' => 60,
            'cacheHour' => 60 * 60,
            'cacheDay' => 60 * 60 * 24,
            'cacheWeek' => 60 * 60 * 24 * 7,
        ];
    }

    private function registerBaseMacro(): void
    {
        QueryBuilder::macro('cache', call_user_func(new CacheMacro));
        EloquentBuilder::macro('cache', call_user_func(new CacheMacro));
    }

    /**
     * Will register every custom
     * @param  string  $macroName
     * @param $ttl
     */
    private function registerCustomMacro(string $macroName, $ttl = null): void
    {
        QueryBuilder::macro($macroName, CacheMacro::customTTL($ttl));
        EloquentBuilder::macro($macroName, CacheMacro::customTTL($ttl));
    }
}
