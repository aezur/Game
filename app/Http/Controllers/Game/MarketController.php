<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Gladiator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MarketController extends Controller
{
    public function show(Request $request): Response
    {
        $gladiators = Gladiator::where('ludus', null)->limit(10)->get();

        return Inertia::render('Market', [
            'data' => $gladiators,
        ]);
    }
}
