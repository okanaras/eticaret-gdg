<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::command('verify:send-mail')->daily();

/**
 * Dakika (0 - 59)
 * Saat (0 - 23)
 * Gun (1 - 31)
 * Ay (1 - 12)
 * Haftanin gunu (0 - 7, burada 0 ve 7 Pazar(0) gununu temsil eder)
 *
 * Yalnizca sali gunleri  saat 14:30 calisacak cron tanimlamasi nasil yapilir?
 */

//  30 14 * * 2 /home/user/scripts/backup.sh // her hafta sali gunu saat 14:30'da calisacak