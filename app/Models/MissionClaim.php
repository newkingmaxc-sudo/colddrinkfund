<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionClaim extends Model
{
    use HasFactory;

    protected $table = 'missions_claimed';

    protected $fillable = [
        'user_id',
        'mission_key',
        'reward_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
