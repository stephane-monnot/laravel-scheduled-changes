<?php

return [
    'models' => [
        'schedule_change' => StephaneMonnot\LaravelScheduledChanges\Models\ScheduleChange::class,
        'scheduled_unit' => StephaneMonnot\LaravelScheduledChanges\Models\ScheduledUnit::class,
    ],
    'table_names' => [
        'schedule_changes' => 'schedule_changes',
        'scheduled_units' => 'scheduled_units',
    ],
];
