<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'url',
        'platform',
        'length',
        'lesson_id'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
