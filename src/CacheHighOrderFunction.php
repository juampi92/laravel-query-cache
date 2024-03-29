<?php

namespace Juampi92\LaravelQueryCache;

use Illuminate\Cache\TaggedCache;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

/**
 * @mixin EloquentBuilder|QueryBuilder
 */
class CacheHighOrderFunction
{
    private string $key;

    /** @var \DateInterval|\DateTimeInterface|int|null */
    private $ttl;

    /** @var EloquentBuilder|QueryBuilder */
    private $query;

    /** @var array<string> */
    private array $tags = [];

    /** @var Repository|TaggedCache */
    private $cache;

    /**
     * CachedQueryBuilder constructor.
     * @param EloquentBuilder|QueryBuilder $query
     * @param string $key
     * @param \DateTimeInterface|\DateInterval|int|null $ttl
     */
    public function __construct($query, string $key, $ttl)
    {
        $this->query = $query;
        $this->key = $key;
        $this->ttl = $ttl;
        $this->tags = [];
        $this->cache = Cache::store();
    }

    public function store(string $name): self
    {
        $this->cache = Cache::store($name);

        return $this;
    }

    /**
     * @param  array<string>  $names
     * @return $this
     */
    public function tags(array $names): self
    {
        $this->tags = $names;

        return $this;
    }

    public function __call($method, $arguments)
    {
        if (count($this->tags)) {
            $this->cache = $this->cache->tags($this->tags);
        }

        if (! $this->ttl) {
            return $this->cache->rememberForever(
                $this->key,
                fn () => $this->callQuery($method, $arguments)
            );
        }

        return $this->cache->remember(
            $this->key,
            $this->ttl,
            fn () => $this->callQuery($method, $arguments)
        );
    }

    /**
     * @param string $method
     * @param array<mixed> $arguments
     * @return mixed
     */
    private function callQuery($method, $arguments)
    {
        $result = $this->query->$method(...$arguments);

        if ($result instanceof EloquentBuilder || $result instanceof QueryBuilder) {
            throw new RuntimeException('You can\'t cache a query. Remember to use ->cache() right before executing the query.');
        }

        return $result;
    }
}
