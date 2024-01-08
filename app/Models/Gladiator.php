<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function ludus(): BelongsTo
    {
        return $this->belongsTo(Ludus::class);
    }
}
