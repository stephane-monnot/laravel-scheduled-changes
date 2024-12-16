<?php

namespace StephaneMonnot\LaravelScheduledChanges\Tests\TestModels;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 */
class User extends Model
{
    protected $fillable = ['email'];
}
