<?php

use StephaneMonnot\LaravelScheduledChanges\Handlers\ChangeModelValueHandler;
use StephaneMonnot\LaravelScheduledChanges\Jobs\HandleChangeJob;
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduleChange;
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduledUnit;
use StephaneMonnot\LaravelScheduledChanges\Tests\TestModels\User;

it('can change model value', function () {
    // Create a test user
    $user = User::create([
        'email' => 'oldemail@example.com',
    ]);

    // Serialize the model
    $serializedModel = serialize($user);

    // Create a ScheduledUnit with the handler's data
    $change = ScheduleChange::create([
        'type' => 'change_model_value',
        'payload' => [
            'attribute' => 'email',
            'value' => 'newemail@example.com',
        ],
        'scheduled_at' => now(),
    ]);

    $unit = $change->units()->create([
        'data' => [
            'serialized_model' => $serializedModel,
        ],
    ]);

    // Instantiate the handler
    $handler = new ChangeModelValueHandler;

    // Execute the handler
    $handler->handle($unit);

    // Assert that the model's attribute was updated
    $this->assertEquals('newemail@example.com', $user->fresh()->email);
});

it('can convert the handler into a dispatchable job', function () {
    // Create a test user
    $user = User::create([
        'email' => 'oldemail@example.com',
    ]);

    // Serialize the model
    $serializedModel = serialize($user);

    // Create a ScheduledUnit with the handler's data
    $unit = ScheduledUnit::create([
        'schedule_change_id' => 1,
        'data' => [
            'type' => 'change_model_value',
            'serialized_model' => $serializedModel,
            'attribute' => 'email',
            'value' => 'newemail@example.com',
        ],
    ]);

    // Instantiate the handler
    $handler = new ChangeModelValueHandler;

    // Convert the handler into a job
    $job = $handler->toJob($unit);

    // Assert that the job is an instance of HandleChangeJob
    $this->assertInstanceOf(HandleChangeJob::class, $job);

    // Assert that the job's handler is the same as the handler
    $this->assertEquals($handler, $job->handler);

    // Assert that the job's unit is the same as the unit
    $this->assertEquals($unit, $job->unit);
});
