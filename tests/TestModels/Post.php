<?php

namespace StephaneMonnot\LaravelScheduledChanges\Tests\TestModels;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 */
class Post extends Model
{
    protected $fillable = ['title', 'content', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
