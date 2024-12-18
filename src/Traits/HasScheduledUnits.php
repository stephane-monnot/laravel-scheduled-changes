<?php

namespace StephaneMonnot\LaravelScheduledChanges\Traits;

use StephaneMonnot\LaravelScheduledChanges\Models\ScheduledUnit;

trait HasScheduledUnits
{
    public function ScheduledUnits()
    {
        return $this->morphMany(ScheduledUnit::class, 'schedulable');
    }
}
