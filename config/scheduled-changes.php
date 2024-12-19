<?php

use StephaneMonnot\LaravelScheduledChanges\Handlers\ChangeModelValueHandler;
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduleChange;
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduledUnit;

return [
    'models' => [
        'schedule_change' => ScheduleChange::class,
        'scheduled_unit' => ScheduledUnit::class,
    ],
    'table_names' => [
        'schedule_changes' => 'schedule_changes',
        'scheduled_units' => 'scheduled_units',
    ],
    'handlers' => [
        'change_model_value' => ChangeModelValueHandler::class,
    ],
];
