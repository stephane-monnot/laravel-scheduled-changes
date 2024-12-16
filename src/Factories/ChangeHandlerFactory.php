<?php

namespace StephaneMonnot\LaravelScheduledChanges\Factories;

use InvalidArgumentException;
use StephaneMonnot\LaravelScheduledChanges\Contracts\ChangeHandler;
use StephaneMonnot\LaravelScheduledChanges\Handlers\ChangeModelValueHandler;

class ChangeHandlerFactory
{
    /**
     * Create a handler instance based on the type of the unit change.
     */
    public static function make(string $type): ChangeHandler
    {
        return match ($type) {
            'change_model_value' => new ChangeModelValueHandler,
            default => throw new InvalidArgumentException("Handler for type {$type} is not defined."),
        };
    }
}
