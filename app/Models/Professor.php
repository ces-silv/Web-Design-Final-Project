<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Professor extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Mutator para encriptar la contraseña antes de guardarla en la base de datos.
    */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getFullName(){
        return $this->first_name . ' ' . $this->last_name;
    }

    public function groups() : HasMany{
        return $this->hasMany(ClassGroup::class);
    }
}
