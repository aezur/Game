<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ludus extends Model
{
    protected $table = 'ludi'; // Weird latin pluralization

    protected $fillable = [
        'name',
        'owner',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function gladiators()
    {
        return $this->hasMany(Gladiator::class, 'ludus');
    }
}
