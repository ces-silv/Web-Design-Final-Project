<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Justification;

class JustificationDocument extends Model
{
    protected $fillable = [
        'justification_id',
        'file_content',
        'file_name',
        'mime_type',
        'size'
    ];

    public function justification(): BelongsTo
    {
        return $this->belongsTo(Justification::class);
    }
}
