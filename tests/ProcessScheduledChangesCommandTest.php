<?php

use Illuminate\Support\Facades\Bus;
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduleChange;
use StephaneMonnot\LaravelScheduledChanges\Tests\TestModels\Post;

it('can process scheduled changes', function () {
    Bus::fake();

    // Create posts
    $post1 = Post::create([
        'title' => 'Post 1',
        'content' => 'Content 1',
        'published_at' => null,
    ]);

    $post2 = Post::create([
        'title' => 'Post 2',
        'content' => 'Content 2',
        'published_at' => null,
    ]);

    // Create a ScheduledUnit with the handler's data
    $change = ScheduleChange::create([
        'type' => 'change_model_value',
        'payload' => [
            'attribute' => 'published_at',
            'value' => '2021-01-01 00:00:00',
        ],
        'scheduled_at' => now(),
    ]);

    $unit1 = $change->units()->create([
        'schedule_change_id' => $change->id,
        'data' => [
            'serialized_model' => serialize($post1),
        ],
    ]);

    $unit2 = $change->units()->create([
        'schedule_change_id' => $change->id,
        'data' => [
            'serialized_model' => serialize($post2),
        ],
    ]);

    $this->artisan('scheduled-changes:process --dispatch')
        ->assertExitCode(0);

    Bus::assertBatched(function ($batch) use ($unit1, $unit2) {
        return $batch->jobs->count() === 2
            && $batch->jobs->contains(fn ($job) => $job[0]->unit->is($unit1))
            && $batch->jobs->contains(fn ($job) => $job[0]->unit->is($unit2));
    });

    // Assert that the model's attribute was not updated
    $this->assertEquals(null, $post1->fresh()->published_at);
    $this->assertEquals(null, $post2->fresh()->published_at);
});
