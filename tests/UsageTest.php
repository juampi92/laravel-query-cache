<?php

namespace Juampi92\LaravelQueryCache\Tests;

use Juampi92\LaravelQueryCache\Tests\stubs\Post;

class UsageTest extends TestCase
{
    /** @test */
    public function should_throw_runtime_exception_if_used_incorrectly()
    {
        $this->expectException(\RuntimeException::class);

        Post::query()->cacheForever('posts:published:count')->whereNotNull('published_at')->count();
    }

    /** @test */
    public function should_throw_argument_error_when_using_cache_without_ttl()
    {
        $this->expectException(\ArgumentCountError::class);

        Post::query()->cache('posts:published:count')->count();
    }
}
