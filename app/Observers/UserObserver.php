<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Notifications\WelcomeMailNotification;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $token = Str::random(40);
        Cache::put('verify_token_' . $token, $user->id,  now()->addMinutes(60));

        $user->notify(new WelcomeMailNotification($token));
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // isDirty() bir alan değiştiğinde kontrol eder.
        if ($user->isDirty('email_verified_at')) {
            Cache::forget('verify_token_' . request()->token);
        }
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