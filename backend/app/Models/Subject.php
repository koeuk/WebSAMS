<?php

namespace App\Models;

use App\Models\Concerns\SerializesCurrentLocale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Subject extends Model
{
    use HasTranslations, SerializesCurrentLocale;

    protected $fillable = ['course_id', 'name', 'code'];

    public array $translatable = ['name'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function classSubjects(): HasMany
    {
        return $this->hasMany(ClassSubject::class);
    }
}
