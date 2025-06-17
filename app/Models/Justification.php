<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class Justification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reason',
        'attachment',
        'status',
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
=======
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
>>>>>>> parent of 3a1f45f (Merge branch 'main' of https://github.com/ces-silv/Web-Design-Final-Project into feature/justifications)
