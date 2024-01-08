<?php

namespace App\DTOs;

class GladiatorDTO
{
    public string|null $name;
    public int|null $strength;
    public int|null $defense;
    public int|null $accuracy;
    public int|null $evasion;
    public int|null $ludus;

    public function __construct(
        string $name,
        int $strength,
        int $defense,
        int $accuracy,
        int $evasion,
        int $ludus
    ) {
        $this->name = $name ?? null;
        $this->strength = $strength ?? null;
        $this->defense = $defense ?? null;
        $this->accuracy = $accuracy ?? null;
        $this->evasion = $evasion ?? null;
        $this->ludus = $ludus ?? null;
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'strength' => $this->strength,
            'defense' => $this->defense,
            'accuracy' => $this->accuracy,
            'evasion' => $this->evasion,
            'ludus_id' => $this->ludus,
        ]);
    }
}
