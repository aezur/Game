<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\Market\MarketPurchaseRequest;
use App\Services\MarketService;
use Illuminate\Http\JsonResponse;
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
            'data' => $this->marketService
                ->getGladiatorsForUser($request->user()),
        ]);
    }

    public function purchase(MarketPurchaseRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Gladiator purchased successfully',
            'data' => $this->marketService
                ->purchaseGladiator($request->user(), $request->validated()['id']),
        ]);
    }
}
