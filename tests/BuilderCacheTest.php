<?php

namespace Juampi92\LaravelQueryCache\Tests;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Juampi92\LaravelQueryCache\Tests\stubs\Post;

class BuilderCacheTest extends TestCase
{
    /** @test */
    public function should_cache_simple_queries()
    {
        // Seed 3 posts
        Post::factory()->count(3)->create();

        // Check that the count works
        $this->assertEquals(
            3,
            Post::query()->cache('post:count', 60)->count(),
            'The amount of posts didnt match'
        );

        // Add more posts
        Post::factory()->count(4)->create();

        // Check that cached amount didn't change
        $this->assertEquals(
            3,
            Post::cache('post:count', 60)->count(),
            'The amount of cached posts didnt match'
        );

        // Check that non-cached amount is correct
        $this->assertEquals(
            3 + 4,
            Post::count(),
            'The amount of non-cached posts didnt match'
        );
    }

    /** @test */
    public function should_cache_complex_queries()
    {
        // Seed 3 posts
        Post::factory()->published()->count(3)->create();
        Post::factory()->published()->writtenBy('juampi92')->count(3)->create();

        $query = Post::writtenBy('juampi92')->published();

        // Get and cache some rows.
        $result = (clone $query)->cacheHour('post:juampi92')->get(['id']);
        $this->assertCount(3, $result);

        // Delete all posts and assert they were deleted.
        Post::truncate();
        $this->assertEmpty(Post::all());

        // Assert the cache still holds the previous data.
        $this->assertEquals(
            $result,
            (clone $query)->cacheHour('post:juampi92')->get(['id'])
        );

        Cache::flush();

        // After flushing the cache, then it resets.
        $this->assertEmpty((clone $query)->cacheHour('post:juampi92')->get(['id']));
    }

    /** @test */
    public function should_cache_paginated_results()
    {
        Post::factory()->count(10)->create();

        $result = Post::cacheHour('post:paginated')->simplePaginate(5);
        $cache = Cache::get('post:paginated');

        $this->assertNotNull($cache);

        $this->assertEquals(
            $cache->first()->id,
            $result->first()->id
        );

        $this->assertEquals(
            $cache->first()->id,
            $result->first()->id
        );
    }

    /** @test */
    public function should_cache_db_query()
    {
        Post::factory()->count(5)->create();

        $result = DB::table('posts')
            ->cacheHour('post:count')
            ->count();
        $cache = Cache::get('post:count');

        $this->assertNotNull($cache);

        $this->assertEquals(5, $cache);
        $this->assertEquals($result, $cache);
    }
}
