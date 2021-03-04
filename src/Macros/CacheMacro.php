<?php

namespace Juampi92\LaravelQueryCache\Macros;

use ArgumentCountError;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Juampi92\LaravelQueryCache\CacheHighOrderFunction;

class CacheMacro
{
    /**
     * @return \Closure
     */
    public function __invoke(): \Closure
    {
        /**
         * @param string $key
         * @param \DateTimeInterface|\DateInterval|int $ttl
         * @return CacheHighOrderFunction
         */
        return function (string $key, $ttl = null): CacheHighOrderFunction {
            if (!$ttl) {
                throw new ArgumentCountError(
                    'Too few arguments to function cache(), $ttl was missing. For fixed-length cache times, use `cacheForever`, `cacheMinute`, `cacheHour`, `cacheDay`, `cacheWeek`'
                );
            }

            /** @var QueryBuilder|EloquentBuilder $this */
            return new CacheHighOrderFunction($this, $key, $ttl);
        };
    }

    /**
     * @param \DateTimeInterface|\DateInterval|int|null $ttl
     * @return \Closure
     */
    public static function customTTL($ttl): \Closure
    {
        return function ($key) use ($ttl): CacheHighOrderFunction {
            /** @var QueryBuilder|EloquentBuilder $this */
            return new CacheHighOrderFunction($this, $key, $ttl);
        };
    }
}
