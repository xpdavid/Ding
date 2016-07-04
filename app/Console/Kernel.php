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
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // empty visitor table every day
        $schedule->call(function () {
            DB::table('visitors')->truncate();
        })->daily();


        // clear day visit count
        $schedule->call(function () {
            DB::table('hits')->update(['day' => 0]);
        })->daily();

        // clear week visit count
        $schedule->call(function () {
            DB::table('hits')->update(['week' => 0]);
        })->weekly();

        // clear month visit count
        $schedule->call(function () {
            DB::table('hits')->update(['month' => 0]);
        })->monthly();
    }
}
