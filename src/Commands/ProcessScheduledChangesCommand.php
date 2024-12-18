<?php

namespace StephaneMonnot\LaravelScheduledChanges\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use StephaneMonnot\LaravelScheduledChanges\Factories\ChangeHandlerFactory;
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduleChange;
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduledUnit;

class ProcessScheduledChangesCommand extends Command
{
    public $signature = 'scheduled-changes:process {--dispatch : Dispatch units to a queue}';

    public $description = '';

    public function handle(): int
    {
        info('Processing scheduled changes...');

        $changes = ScheduleChange::where('status', 'pending')
            ->where('scheduled_at', '<=', now())
            ->with('units')
            ->get();

        foreach ($changes as $change) {
            try {
                // Log the processing of the main change
                $this->info("Processing batch ID {$change->id}");

//                $change->update(['status' => 'processing']);

                $jobs = [];
                /** @var ScheduledUnit $unit */
                foreach ($change->units as $unit) {
                    try {
                        // Retrieve the appropriate handler for this unit
                        $handler = ChangeHandlerFactory::make($change->type);

                        if ($this->option('dispatch')) {
                            // Dispatch the unit to a queue (asynchronous)
                            $jobs[] = $handler->toJob($unit);
                        } else {
                            // Execute the unit directly (synchronous)
                            $handler->handle($unit);

                            // Mark the unit as executed
                            $unit->update(['status' => 'executed']);
                        }

                        $this->info("Processed unit ID {$unit->id}");
                    } catch (Exception $e) {
                        // Log the error and mark the unit as failed
                        $unit->update(['status' => 'failed']);
                        $this->error("Failed to process unit ID {$unit->id}: ".$e->getMessage());
                    }
                }

                if ($this->option('dispatch')) {
                    // Dispatch all jobs to the queue and add closure to mark the unit as executed
                    $batch = Bus::batch(array_map(fn ($job) => [$job, function () use ($job) {
                        $job->unit->update(['status' => 'executed']);
                    }], $jobs));

                    $batch->then(function () use ($change) {
                        $change->update(['status' => 'executed']);
                    })->dispatch();
                }

                // Mark the batch as executed if all units are processed
                if ($change->units()->where('status', '!=', 'executed')->doesntExist()) {
                    $change->update(['status' => 'executed']);
                }

            } catch (Exception $e) {
                $change->update(['status' => 'failed']);
                $this->error("Failed to process batch ID {$change->id}: ".$e->getMessage());
            }
        }

        return self::SUCCESS;
    }
}
