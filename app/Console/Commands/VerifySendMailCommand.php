<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifySendMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:send-mail {user? : UserID degerini alir} {--Q|queue} {--T|tc=}';
    /**
     * command, argument, option degerli var.
     * Options {--queue} seklinde ise sadece kullanildiginda true, kullanilmadiginda false doner.
     * Options {--Q|queue} seklinde tek harf ile kisalma verebiliriz fakat tek tre ile alinmasi gerekli.
     * {user : aciklama} - 'php artisan commandAdi -h' yazildiginda aciklama gosterilir. ORN: 'php artisan verify:send-mail 20 -Q -T=520'
     * Eger ki rgument {user?} seklinde sonunda ? varsa command te verilmesi zorunlu degildir.
     * Aksi takdirde verilmesi zorunludur.
     *
     * Description alani 'php artisan' yazildiginda commandlarin sag tarafindaki aciklama bolumleridir.
     */
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email adresini dogrulamayan kullanicilara mail atan command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $arguments = $this->arguments(); // tumn argumanlari okumak icin
        // $user = $this->argument('user');
        // $queue = $this->option('queue');
        // $tc = $this->option('tc');

        // dd($arguments);
        // dd($user, $queue, $tc);
        // dd('Hello World');


        /** Command tablo ornegi */
        $users = User::query()->select('name', 'email')->get();

        $this->info('Kullanicilarin ad ve email bilgileri tablosu:');
        $this->table(
            ['Name', 'Email'],
            $users
        );

        $this->newLine(2); // satir atlatma

        /** ProgressBar ornegi */
        $this->warn('ProgressBar kullanimi:');
        $userBar = $this->withProgressBar($users, function (User $user) {
            echo $user->name;
        });

        $this->newLine(2);
        $this->info('bilgilendirme logu');
        $this->newLine(2);
        $this->line('satir yazisi');
        $this->newLine(2);
        $this->error('Hata mesaji');
        $this->newLine(2);
        $this->warn('Uyarı mesaji');
        $this->newLine(2);
    }
}