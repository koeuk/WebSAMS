<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Course extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'code', 'description'];

    public array $translatable = ['name', 'description'];

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
