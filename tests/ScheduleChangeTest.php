<?php

use Carbon\Carbon;
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduleChange;

it('can create a scheduled change', function () {
    Carbon::setTestNow(Carbon::now());

    $change = ScheduleChange::createChange('change_model_value', [
        'attribute' => 'test',
        'value' => 'test',
    ], now());

    expect($change->type)->toBe('change_model_value');
    expect($change->payload)->toBe([
        'attribute' => 'test',
        'value' => 'test',
    ]);
    expect($change->status)->toBe('pending');
    expect($change->scheduled_at->toIso8601String())->toBe(now()->toIso8601String());
});
