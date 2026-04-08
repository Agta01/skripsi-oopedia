<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbutSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'started_at',
        'submitted_at',
        'duration_seconds',
        'run_count',
        'is_completed',
        'final_code',
    ];

    protected $casts = [
        'started_at'    => 'datetime',
        'submitted_at'  => 'datetime',
        'is_completed'  => 'boolean',
        'duration_seconds' => 'integer',
        'run_count'     => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(VirtualLabTask::class, 'task_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Return human-readable duration (mm:ss or hh:mm:ss).
     */
    public function formattedDuration(): string
    {
        $secs = $this->duration_seconds;
        $h = intdiv($secs, 3600);
        $m = intdiv($secs % 3600, 60);
        $s = $secs % 60;

        if ($h > 0) {
            return sprintf('%d:%02d:%02d', $h, $m, $s);
        }
        return sprintf('%d:%02d', $m, $s);
    }
}
