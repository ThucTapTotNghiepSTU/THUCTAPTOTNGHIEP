<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ITCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'category',
        'status'
    ];

    public static function getCategories()
    {
        return [
            'Web Development',
            'Mobile Development',
            'Data Science',
            'Machine Learning',
            'Cloud Computing',
            'Cybersecurity'
        ];
    }
}
