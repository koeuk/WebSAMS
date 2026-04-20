<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcuseRequest extends Model
{
    protected $fillable = [
        'student_id', 'attendance_id', 'reason',
        'status', 'reviewed_by', 'reviewed_at', 'reviewer_note',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function student()    { return $this->belongsTo(User::class, 'student_id'); }
    public function attendance() { return $this->belongsTo(Attendance::class); }
    public function reviewer()   { return $this->belongsTo(User::class, 'reviewed_by'); }
}
