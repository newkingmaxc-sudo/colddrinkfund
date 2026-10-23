<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedEnvelopeClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'red_envelope_id',
        'user_id',
        'amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function redEnvelope()
    {
        return $this->belongsTo(RedEnvelope::class);
    }
}
