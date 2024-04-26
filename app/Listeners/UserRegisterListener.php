<?php

namespace App\Listeners;

use App\Notifications\WelcomeMailNotification;
use Illuminate\Support\Str;
use App\Mail\UserWelcomeMail;
use App\Events\UserRegisterEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class UserRegisterListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegisterEvent $event): void
    {
        $token = Str::random(40);

        // veriify_token_40char
        // on bellekte 60 dk boyunca tutulacak
        Cache::put('verify_token_' . $token, $event->user->id,  now()->addMinutes(60));

        $event->user->notify(new WelcomeMailNotification($token));

        // Mail::to($event->user->email)->send(new UserWelcomeMail($event->user, $token));
    }
}