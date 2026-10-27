<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('sitemap:generate')
    ->everyMinute()
    ->withoutOverlapping();

Schedule::command('visitors:process')
    ->name('visitors-process')
    ->everyMinute()
    ->timezone(config('app.timezone', 'Asia/Jakarta'))
    ->withoutOverlapping(10)
    ->onOneServer();
