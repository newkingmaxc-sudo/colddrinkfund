<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_number',
        'source',
        'status',
        'prize_name',
        'prize_amount',
    ];

    protected $casts = [
        'prize_amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
