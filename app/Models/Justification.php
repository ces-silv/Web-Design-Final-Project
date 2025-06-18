<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\JustificationDocument;
use App\Models\UniversityClass;
use App\Models\User;

class Justification extends Model
{
    protected $fillable = [
        'description',
        'start_date',
        'end_date',
        'university_class_id',
        'student_id'
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(UniversityClass::class, 'university_class_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function document(): HasOne
    {
        return $this->hasOne(JustificationDocument::class);
    }
}
