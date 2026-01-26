<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\User;

class PostOpRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->added_by = auth()->id();
            }
             if ($model->patient_id) {
                $model->bed_number = Patient::find($model->patient_id)?->bed_number;
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

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
