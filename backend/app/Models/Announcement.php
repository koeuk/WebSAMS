<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'author_id', 'title', 'body', 'audience', 'class_id', 'published_at',
    ];

    protected $casts = ['published_at' => 'datetime'];

    public function author()      { return $this->belongsTo(User::class, 'author_id'); }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
}
