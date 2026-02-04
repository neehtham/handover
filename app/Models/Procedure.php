<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\User;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'patient_name',
        'bed_number',
        'procedure_name',
        'status',
        'remarks',
        'finished_by',
        'finished_at',
        'fulfilled_by',
        'fulfilled_at',
    ];

    protected static function booted()
    {
        parent::booted();
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

    public function finishedBy()
    {
        return $this->belongsTo(User::class, 'finished_by');
    }
}
