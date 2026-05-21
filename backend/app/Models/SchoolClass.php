<?php

namespace App\Models;

use App\Models\Concerns\SerializesCurrentLocale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class SchoolClass extends Model
{
    use HasTranslations, SerializesCurrentLocale;

    protected $fillable = ['name', 'section', 'academic_year'];

    public array $translatable = ['name'];

    public function subjects(): HasMany
    {
        return $this->hasMany(ClassSubject::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_student', 'school_class_id', 'student_id')->withPivot('id');
    }
}
