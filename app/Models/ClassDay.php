<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassDay extends Model
{
    protected $fillable = [
        'group_id',
        'weekday',
        'start_time',
        'duration_in_min',
    ];

    public function group() : BelongsTo {
        return $this->belongsTo(ClassGroup::class);
    }
}
