<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pacs()
    {
        return $this->hasMany(Pac::class, 'added_by');
    }

    public function fulfilledPacs()
    {
        return $this->hasMany(Pac::class, 'fulfilled_by');
    }

    public function procedures()
    {
        return $this->hasMany(Procedure::class, 'added_by');
    }

    public function finishedProcedures()
    {
        return $this->hasMany(Procedure::class, 'finished_by');
    }

    public function postOpRequests()
    {
        return $this->hasMany(PostOpRequest::class, 'added_by');
    }

    public function completedPostOpRequests()
    {
        return $this->hasMany(PostOpRequest::class, 'completed_by');
    }

    public function chronicRounds()
    {
        return $this->hasMany(ChronicRound::class, 'doctor_id');
    }
}
