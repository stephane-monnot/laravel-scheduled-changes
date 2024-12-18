<?php

namespace StephaneMonnot\LaravelScheduledChanges\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use StephaneMonnot\LaravelScheduledChanges\Contracts\ChangeHandler;
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduledUnit;

class HandleChangeJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ChangeHandler $handler;

    public ScheduledUnit $unit;

    public function __construct(ChangeHandler $handler, ScheduledUnit $unit)
    {
        $this->handler = $handler;
        $this->unit = $unit;
    }

    public function handle(): void
    {
        $this->handler->handle($this->unit);
    }
}
