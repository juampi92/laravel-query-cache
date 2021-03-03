<?php

namespace Juampi92\LaravelQueryCache\Tests\stubs\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Juampi92\LaravelQueryCache\Tests\stubs\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'author_username' => $this->faker->userName,
        ];
    }

    public function published()
    {
        return $this->state([
            'published_at' => now()->subDay(),
        ]);
    }

    public function writtenBy(string $authorName)
    {
        return $this->state([
            'author_username' => $authorName,
        ]);
    }
}
