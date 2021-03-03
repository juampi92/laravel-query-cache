<?php

namespace Juampi92\LaravelQueryCache\Tests;

use Illuminate\Support\Facades\Cache;
use Juampi92\LaravelQueryCache\Tests\stubs\Post;

class UsageTest extends TestCase
{
    /** @test */
    public function should_throw_runtime_exception_if_used_incorrectly()
    {
        $this->expectException(\RuntimeException::class);

        Post::query()->cache('posts:published:count')->whereNotNull('published_at')->count();
    }
}
