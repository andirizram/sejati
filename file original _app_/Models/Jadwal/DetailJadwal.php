<?php

namespace App\Models\Jadwal;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface DetailJadwal
{
    public function jadwal(): MorphOne;
}