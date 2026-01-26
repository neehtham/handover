<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_discharged' => 'boolean',
        'discharged_at' => 'datetime',
    ];

    public function pacs()
    {
        return $this->hasMany(Pac::class);
    }

    public function procedures()
    {
        return $this->hasMany(Procedure::class);
    }

    public function postOpRequests()
    {
        return $this->hasMany(PostOpRequest::class);
    }

    public function chronicRounds()
    {
        return $this->hasMany(ChronicRound::class);
    }
}
