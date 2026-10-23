<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ref_id',
        'ref_by',
        'username',
        'code',
        'pcode',
        'text_pass',
        'type',
        'balance',
        'phone',
        'sign_every_day',
        'is_agent',
        'agent_city',
        'agent_title',
        'agent_commission_pct',
        'agent_total_earnings',
        'agent_appointed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_frozen' => 'boolean',
    ];
    
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    
    public function spins()
    {
        return $this->hasMany(Spin::class, 'referrer_id');
    }
    
    public function referrer()
    {
        return $this->belongsTo(User::class, 'ref_by', 'ref_id');
    }
    
    public function referrals()
    {
        return $this->hasMany(User::class, 'ref_by', 'ref_id');
    }

    public function isAgent()
    {
        return (bool)$this->is_agent;
    }

    public function agentApplications()
    {
        return $this->hasMany(AgentApplication::class);
    }
}
