<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasPembuat
{
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_pembuat');
    }
}