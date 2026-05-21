<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Semester extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'academic_year', 'start_date', 'end_date'];

    public array $translatable = ['name'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
}
