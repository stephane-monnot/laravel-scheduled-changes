<?php

namespace StephaneMonnot\LaravelScheduledChanges\Handlers;

use Illuminate\Database\Eloquent\Model;
use StephaneMonnot\LaravelScheduledChanges\Contracts\ChangeHandler;
use StephaneMonnot\LaravelScheduledChanges\Jobs\HandleChangeJob;
use StephaneMonnot\LaravelScheduledChanges\Models\ScheduledUnit;

class ChangeModelValueHandler implements ChangeHandler
{
    public function handle(ScheduledUnit $unit): void
    {
        $data = [
            ...$unit->scheduleChange->payload,
            ...$unit->data,
        ];

        if (! isset($data['serialized_model'], $data['attribute'], $data['value'])) {
            throw new \InvalidArgumentException('Invalid data provided for ChangeModelValueHandler.');
        }

        // Unserialize the model instance
        $model = unserialize($data['serialized_model']);

        if (! $model || ! $model instanceof Model || ! method_exists($model, 'update')) {
            throw new \RuntimeException('Invalid serialized model provided.');
        }

        // Update the specified attribute with the new value
        $model->update([
            $data['attribute'] => $data['value'],
        ]);

        info('Updated model '.get_class($model)." (ID: {$model->getKey()}) - {$data['attribute']} changed to {$data['value']}");
    }

    public function toJob(ScheduledUnit $unit): object
    {
        return new HandleChangeJob($this, $unit);
    }
}
