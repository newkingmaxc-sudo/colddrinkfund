<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedEnvelope extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'total_amount',
        'remaining_amount',
        'max_claimers',
        'claimed_count',
        'min_reward',
        'max_reward',
        'status',
    ];

    public function claims()
    {
        return $this->hasMany(RedEnvelopeClaim::class);
    }
}
