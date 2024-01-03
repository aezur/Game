<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Services\MarketService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MarketController extends Controller
{
    public function __construct(
        protected MarketService $marketService
    ) {
    }

    public function show(Request $request): Response
    {
        return Inertia::render('Market', [
            'data' => $this->marketService->getGladiators(),
        ]);
    }
}
