<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ludus extends Model
{
    use HasFactory;

    protected $table = 'ludi'; // Weird pluralization

    protected $fillable = [
        'name',
        'owner',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }
}
