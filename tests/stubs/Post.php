<?php

namespace Juampi92\LaravelQueryCache\Tests\stubs;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at');
    }

    public function scopeWrittenBy(Builder $query, string $username): void
    {
        $query->where('author_username', $username);
    }
}
