<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'college_image',
        'state_id',
        'city_id',
       
        'application_deadline',
        'fees_range',
        'email',
        'phone',
        'website',
         'status', // 👈 Add this,
         'course_ids', // 👈 Add this
    ];

    protected $casts = [
        'application_deadline' => 'date',
         'course_ids' => 'array', // 👈 Auto-convert JSON to array
    ];

    /**
     * Get the state that owns the college.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the city that owns the college.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    
    /**
     * Helper to get programs as an array instead of string
     */
    public function getProgramsArrayAttribute()
    {
        return array_map('trim', explode(',', $this->programs));
        
    }

// app/Models/College.php
public function getCourseNamesAttribute()
{
    if (empty($this->course_ids)) {
        return [];
    }
    return \App\Models\Course::whereIn('id', $this->course_ids)
        ->pluck('name')
        ->toArray();
}
}