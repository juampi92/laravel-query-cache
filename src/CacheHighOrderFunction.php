<?php

namespace Juampi92\LaravelQueryCache;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Cache;

class CacheHighOrderFunction
{
    private string $key;

    /** @var \DateInterval|\DateTimeInterface|int|null */
    private $ttl;

    private $query;

    /**
     * CachedQueryBuilder constructor.
     * @param string $key
     * @param \DateTimeInterface|\DateInterval|int|null $ttl
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     */
    public function __construct(string $key, $ttl, $query)
    {
        $this->key = $key;
        $this->ttl = $ttl;
        $this->query = $query;
    }

    public function __call($method, $arguments)
    {
        if (! $this->ttl) {
            return Cache::rememberForever(
                $this->key,
                fn () => $this->callQuery($method, $arguments)
            );
        }

        return Cache::remember(
            $this->key,
            $this->ttl,
            fn () => $this->callQuery($method, $arguments)
        );
    }

    private function callQuery($method, $arguments)
    {
        $result = $this->query->$method(...$arguments);

        if ($result instanceof EloquentBuilder || $result instanceof QueryBuilder) {
            throw new \RuntimeException('You cant cache a query. Remember to use ->cache() right before executing the query');
        }

        return $result;
    }
}
