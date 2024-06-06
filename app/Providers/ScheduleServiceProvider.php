<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Jadwal\Jadwal;

class ScheduleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share colliding schedule count with all views
        View::composer('*', function ($view) {
            $collidingScheduleCount = Jadwal::getTabrakan()->count();
            $view->with('collidingScheduleCount', $collidingScheduleCount);
        });
    }

    public function register()
    {
        //
    }
}
