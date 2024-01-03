<?php

namespace App\Services;

use App\Models\Gladiator;

class MarketService
{
    public function getGladiators()
    {
        $gladiators = Gladiator::where('ludus', null)->limit(10)->get();

        return $gladiators;
    }
}
