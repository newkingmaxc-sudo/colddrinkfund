<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaultAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'total_interest_earned',
        'last_interest_at',
    ];

    protected $casts = [
        'balance'               => 'float',
        'total_interest_earned' => 'float',
        'last_interest_at'      => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
