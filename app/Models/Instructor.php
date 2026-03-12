<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instructor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'bio',
        'website'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }
}
