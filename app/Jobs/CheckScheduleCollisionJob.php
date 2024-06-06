<?php

namespace App\Jobs;

use App\Models\Jadwal\Jadwal;
use App\Notifications\ScheduleCollisionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckScheduleCollisionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jadwal;

    public function __construct(Jadwal $jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function handle()
    {
        $collisions = Jadwal::getTabrakan();

        if ($collisions->contains('id', $this->jadwal->id)) {
            $this->jadwal->pembuat->notify(new ScheduleCollisionNotification($this->jadwal));
        }
    }
}
