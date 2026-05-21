<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TimeSlot extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'start_time', 'end_time', 'type'];

    public array $translatable = ['name'];
}
