<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrSession extends Model
{
    protected $fillable = [
        'class_subject_id', 'teacher_id', 'time_slot_id',
        'token', 'date', 'expires_at', 'is_active',
    ];

    protected $casts = ['expires_at' => 'datetime', 'is_active' => 'boolean', 'date' => 'date'];

    public function classSubject() { return $this->belongsTo(ClassSubject::class); }
    public function teacher()      { return $this->belongsTo(User::class, 'teacher_id'); }
    public function timeSlot()     { return $this->belongsTo(TimeSlot::class); }
}
