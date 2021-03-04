<?php

namespace Juampi92\LaravelQueryCache\Tests;

use Illuminate\Support\Facades\Cache;
use Juampi92\LaravelQueryCache\Tests\stubs\Post;

class BuilderCacheTest extends TestCase
{
    /** @test */
    public function should_work_for_simple_queries()
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
    public function should_work_for_complex_queries()
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


}
