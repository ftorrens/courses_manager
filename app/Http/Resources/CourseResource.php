<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,

            'instructor' => [
                'id' => $this->instructor?->id,
                'name' => $this->instructor?->name,
            ],

            'lessons_count' => $this->lessons_count,
            'rating_avg' => $this->when(isset($this->rating_avg), round($this->rating_avg, 2)),
            'created_at' => $this->created_at
        ];
    }
}