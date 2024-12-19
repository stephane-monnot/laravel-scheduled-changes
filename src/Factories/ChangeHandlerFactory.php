<?php

namespace StephaneMonnot\LaravelScheduledChanges\Factories;

use InvalidArgumentException;
use StephaneMonnot\LaravelScheduledChanges\Contracts\ChangeHandler;

class ChangeHandlerFactory
{
    /**
     * Create a handler instance based on the type of the unit change.
     */
    public static function make(string $type): ChangeHandler
    {
        // use config to get the handler class
        $handlerClass = config("scheduled-changes.handlers.{$type}");

        if (! $handlerClass) {
            throw new InvalidArgumentException("Handler for type {$type} is not defined.");
        }

        return new $handlerClass;
    }
}
