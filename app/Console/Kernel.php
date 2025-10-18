<?php

namespace App\Console;

use App\Console\Commands\MakeAdmin;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        MakeAdmin::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        //
    }
}
