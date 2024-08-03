<?php

namespace App\Observers;

use App\Models\Profile;
use App\Models\User;

class UserObserve
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Profile::create([
            'user_id' => $user->id,
            'phone' => '',
            'image' => '',
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        Profile::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'phone' => '',
            'image' => '',
        ]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
