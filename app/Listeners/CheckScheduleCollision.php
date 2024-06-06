<?php

namespace App\Listeners;

use App\Events\ScheduleUpdated;
use App\Jobs\CheckScheduleCollisionJob;

class CheckScheduleCollision
{
    public function handle(ScheduleUpdated $event)
    {
        CheckScheduleCollisionJob::dispatch($event->jadwal);
    }
}

