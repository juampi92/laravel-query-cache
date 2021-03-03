<?php

namespace Juampi92\LaravelQueryCache\Tests;

use Illuminate\Support\Facades\Cache;
use Juampi92\LaravelQueryCache\Tests\stubs\Post;

class CacheTTLTest extends TestCase
{
    /** @test */
    public function should_cache_for_a_minute()
    {
        Cache::shouldReceive('remember')
            ->twice()
            ->withArgs(function ($name, $ttl) {
                $this->assertEquals('post:count', $name);
                $this->assertEquals(60, $ttl);

                return true;
            });

        Post::cache('post:count', 60)->count();
        Post::cacheMinute('post:count')->get();
    }

    /** @test */
    public function should_cache_for_an_hour()
    {
        Cache::shouldReceive('remember')
            ->twice()
            ->withArgs(function ($name, $ttl) {
                $this->assertEquals('post:count', $name);
                $this->assertEquals(60 * 60, $ttl);

                return true;
            });

        Post::cache('post:count', 60 * 60)->count();
        Post::cacheHour('post:count')->get();
    }

    /** @test */
    public function should_cache_for_a_day()
    {
        Cache::shouldReceive('remember')
            ->twice()
            ->withArgs(function ($name, $ttl) {
                $this->assertEquals('post:count', $name);
                $this->assertEquals(60 * 60 * 24, $ttl);

                return true;
            });

        Post::cache('post:count', 60 * 60 * 24)->count();
        Post::cacheDay('post:count')->get();
    }

    /** @test */
    public function should_cache_for_a_week()
    {
        Cache::shouldReceive('remember')
            ->twice()
            ->withArgs(function ($name, $ttl) {
                $this->assertEquals('post:count', $name);
                $this->assertEquals(60 * 60 * 24 * 7, $ttl);

                return true;
            });

        Post::cache('post:count', 60 * 60 * 24 * 7)->count();
        Post::cacheWeek('post:count')->get();
    }

    /** @test */
    public function should_use_forever_if_no_ttl_specified()
    {
        Cache::shouldReceive('remember')->never();
        Cache::shouldReceive('rememberForever')
            ->once()
            ->withArgs(function ($name) {
                $this->assertEquals('post:count:44', $name);

                return true;
            });

        Post::cache('post:count:44')->count();
    }
}
