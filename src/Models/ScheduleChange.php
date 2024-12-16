<?php

namespace StephaneMonnot\LaravelScheduledChanges\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property string $type
 * @property array $payload
 * @property string $status
 * @property Carbon $scheduled_at
 * @property \Illuminate\Database\Eloquent\Collection $units
 */
class ScheduleChange extends Model
{
    protected $fillable = ['type', 'payload', 'status', 'scheduled_at'];

    protected $casts = [
        'payload' => 'array',
        'scheduled_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('scheduled-changes.table_name') ?: parent::getTable());
    }

    public function units(): HasMany
    {
        return $this->hasMany(ScheduledUnit::class);
    }

    static public function createChange(string $type, array $payload, Carbon $scheduledAt): self
    {
        return self::create([
            'type' => $type,
            'payload' => $payload,
            'status' => 'pending',
            'scheduled_at' => $scheduledAt,
        ]);
    }
}
