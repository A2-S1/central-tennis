<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedulers
Schedule::command('centraltennis:fetch-atp')->dailyAt('03:00');
Schedule::command('centraltennis:fetch-wta')->dailyAt('03:10');
Schedule::command('centraltennis:fetch-tournaments')->dailyAt('03:20');
