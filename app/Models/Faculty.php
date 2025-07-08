<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    protected $fillable = [
        'name',
    ];

    public function classes(): HasMany {
        return $this->hasMany(UniversityClass::class);
    }

}
