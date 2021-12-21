<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ConsultaOdometro::class,
        Commands\EnviarEventosSSL::class,
        Commands\ConsultaOdometroChevy::class,
        Commands\CierreEntragaOperacion::class,
        Commands\MadDisponibilidad::class,
        Commands\CheckGpsLocationJob::class,
        Commands\SolpedConfirm::class,
        Commands\Providers::class,
        Commands\Vehicle::class,
        Commands\Services::class,
        Commands\Contracts::class,
        Commands\KmChevystar::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('triggers:vehicles')
        //     ->cron('* * * * *')
        //     ->sendOutputTo(storage_path('logs/outputssl.log'));
        // $schedule->command('trigger:providers');
        // $schedule->command('triggers:services');
        $schedule->command('kilometros:kmChevystar');
        // $schedule->command('command:solpedConfirm');

        /*$schedule->command('con:ultrack')
            ->cron('13 8 * * *')
            ->sendOutputTo(storage_path('logs/output.log'));*/
            //->dailyAt('10:30')

            // ->cron('33 19 * * *')
            // )
            

        // $schedule->command('inspire')
        //          ->hourly();

        

    }
}

// http://programacion.net/articulo/gestionando_cronjobs_con_laravel_1091
// https://help.1and1.com/hosting-c37630/scripts-and-programming-languages-c85099/cron-jobs-c37727/delete-a-cron-job-a757264.html
// https://www.garron.me/en/bits/specify-editor-crontab-file.html
