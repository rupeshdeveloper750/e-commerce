<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = ['causer_type', 'causer_id', 'action', 'model', 'record_id', 'details'];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    /**
     * Get the causer of the action (Admin or User).
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Log an activity
     */
    public static function log(string $action, ?string $model = null, ?int $recordId = null, ?array $details = null): self
    {
        $causer = auth()->user() ?: auth('admin')->user();

        return self::create([
            'causer_type' => $causer ? get_class($causer) : null,
            'causer_id' => $causer ? $causer->id : null,
            'action' => $action,
            'model' => $model,
            'record_id' => $recordId,
            'details' => $details,
        ]);
    }
}
