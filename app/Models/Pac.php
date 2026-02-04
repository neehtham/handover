<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Patient;
use App\Models\User;

class Pac extends Model
{
    use HasFactory;

//    protected $guarded = [];
    protected $fillable = [
        'patient_id',
        'fulfilled_by',
        'is_fulfilled',
        'added_by',
        'bed_number',
        'patient_name',
        'status',
        'fulfilled_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->added_by = auth()->id();
            }
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function fulfilledBy()
    {
        return $this->belongsTo(User::class, 'fulfilled_by');
    }
}
