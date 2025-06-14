<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniversityClass extends Model
{

    protected $fillable = [
        'name',
        'faculty_id',
    ];

    protected $table = 'classes';

    public function faculty() : BelongsTo {
        return $this->belongsTo(Faculty::class);
    }

    public function groups() : HasMany {
        return $this->hasMany(ClassGroup::class, 'class_id');
    }
}
