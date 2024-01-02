<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class CreateNewLudus
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $event->user->ludus()->create([
            'name' => $event->user->name."'s Ludus",
            'owner' => $event->user->id,
        ]);
    }
}
