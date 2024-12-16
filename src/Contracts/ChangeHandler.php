<?php

namespace StephaneMonnot\LaravelScheduledChanges\Contracts;

use StephaneMonnot\LaravelScheduledChanges\Models\ScheduledUnit;

interface ChangeHandler
{
    /**
     * Execute the scheduled unit change.
     */
    public function handle(ScheduledUnit $unit): void;

    /**
     * Convert the handler into a dispatchable job.
     */
    public function toJob(ScheduledUnit $unit): object;
}
