<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    protected $fillable = ['name', 'section', 'academic_year'];

    public function subjects(): HasMany
    {
        return $this->hasMany(ClassSubject::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_student', 'school_class_id', 'student_id');
    }
}
