<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Announcement extends Model
{
    use HasTranslations;

    protected $fillable = [
        'author_id', 'title', 'body', 'audience', 'class_id', 'published_at',
    ];

    protected $casts = ['published_at' => 'datetime'];

    public array $translatable = ['title', 'body'];

    public function author()      { return $this->belongsTo(User::class, 'author_id'); }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
}
