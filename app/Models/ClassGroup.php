<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassGroup extends Model
{

    protected $fillable = [
        'class_id',
        'professor_id'
    ];

    public function class() : BelongsTo {
        return $this->belongsTo(UniversityClass::class);
    }

    public function days() : HasMany {
        return $this->hasMany(ClassDay::class, 'group_id');
    }

    public function professor() : BelongsTo {
        return $this->belongsTo(Professor::class);
    }
}
