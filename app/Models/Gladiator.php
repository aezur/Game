<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gladiator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'strength',
        'defense',
        'accuracy',
        'evasion',
    ];

    public function ludus()
    {
        return $this->belongsTo(Ludus::class);
    }
}
