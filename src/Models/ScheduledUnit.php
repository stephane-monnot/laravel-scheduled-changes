<?php

namespace StephaneMonnot\LaravelScheduledChanges\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property array $data
 * @property ScheduleChange $scheduleChange
 */
class ScheduledUnit extends Model
{
    protected $fillable = ['schedule_change_id', 'data', 'status'];

    protected $casts = [
        'data' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('scheduled-changes.table_names.scheduled_units') ?: parent::getTable());
    }

    public function scheduleChange(): BelongsTo
    {
        return $this->belongsTo(ScheduleChange::class);
    }

    public function schedulable(): MorphTo
    {
        return $this->morphTo('schedulable');
    }
}
