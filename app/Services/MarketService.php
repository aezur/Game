<?php

namespace App\Services;

use App\Models\Gladiator;
use App\Models\MarketAccess;
use App\Models\User;
use Illuminate\Support\Carbon;

class MarketService
{
    public function getGladiatorsForUser(User $user): array
    {
        $today = Carbon::today();

        // Check if the user has already accessed the market today
        $access = MarketAccess::firstOrNew([
            'user_id' => $user->id,
            'date' => $today,
        ]);

        if ($access->exists) {
            // If they have, generate the gladiators using the stored seed
            return $this->generateGladiators($access);
        } else {
            // If not, generate the gladiators and store the seed
            $access->seed = $this->generateSeed();
            $gladiators = $this->generateGladiators($access);
            $access->save();

            return $gladiators;
        }
    }

    public function purchaseGladiator(User $user, int $id): Gladiator|string
    {
        $today = Carbon::today();

        // We need the user's access record to get the seed and purchased gladiators
        $access = MarketAccess::where([
            'user_id' => $user->id,
            'date' => $today,
        ])->first();

        if (!$access) {
            throw new \Exception('No market access record found for user '.$user->id);
        }

        // Check if the gladiator has already been purchased
        $purchased = explode(',', $access->purchased);
        if (in_array($id, $purchased)) {
            abort(400, 'Gladiator has already been purchased');
        }

        // Get the gladiator from the list of generated gladiators
        $gladiator = $this->generateGladiators($access)[$id];

        // Create the gladiator and associate it with the user's ludus
        $gladiator = Gladiator::create($gladiator->toArray());
        $gladiator->ludus()->associate($user->ludus)->save();

        // Add the gladiator to the list of purchased gladiators
        $access->purchased = $access->purchased
            ? $access->purchased.','.$id
            : $id;
        $access->save();

        return $gladiator;
    }

    protected function generateSeed(): string
    {
        for ($i = 0; $i < config('market.gladiators_per_day'); ++$i) {
            $seeds[] = mt_rand();
        }

        return implode(',', $seeds);
    }

    protected function generateGladiators(MarketAccess $access): array
    {
        $gladiators = [];
        $seeds = explode(',', $access->seed);

        // Get the list of gladiators that have already been purchased today
        $todaysPurchases = $access->purchased
            ? explode(',', $access->purchased)
            : [];

        // Generate a set of gladiators
        for ($i = 0; $i < config('market.gladiators_per_day'); ++$i) {
            // Seed the random number generator
            mt_srand($seeds[$i]);

            $gladiator = new Gladiator([
                'name' => $this->generateName($seeds[$i]),
                'strength' => mt_rand(config('market.default_min_stat'), config('market.default_max_stat')),
                'defense' => mt_rand(config('market.default_min_stat'), config('market.default_max_stat')),
                'accuracy' => mt_rand(config('market.default_min_stat'), config('market.default_max_stat')),
                'evasion' => mt_rand(config('market.default_min_stat'), config('market.default_max_stat')),
            ]);

            $gladiator->id = $i;
            $gladiator->price = $this->generatePrice($gladiator, $seeds[$i]);
            if (in_array($i, $todaysPurchases)) {
                $gladiator->purchased = true;
            }

            $gladiators[] = $gladiator;
        }

        return $gladiators;
    }

    protected function generatePrice(Gladiator $gladiator, int $seed): int
    {
        $totalStats = $gladiator->strength
            + $gladiator->defense
            + $gladiator->accuracy
            + $gladiator->evasion;

        // Normalize the input to a 0-1 range
        $normalized = ($totalStats - config('market.default_min_total'))
            / (config('market.default_max_total')
            - config('market.default_min_total'));

        // Scale the normalized number to the desired range
        $scaled = $normalized
            * (config('market.default_max_price') - config('market.default_min_price'))
            + config('market.default_min_price');

        $randomness = mt_rand(
            -config('market.price_randomizer'),
            config('market.price_randomizer'),
        );

        $price = floor($scaled + $randomness);

        return $price;
    }

    public function generateName(int $seed): string
    {
        $names = include __DIR__.'/../Data/gladiator_names.php';
        mt_srand($seed);

        return $names[mt_rand(0, count($names) - 1)];
    }
}
