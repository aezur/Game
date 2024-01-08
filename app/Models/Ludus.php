<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ludus extends Model
{
    protected $table = 'ludi'; // Weird latin pluralization

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function gladiators()
    {
        return $this->hasMany(Gladiator::class);
    }
}
