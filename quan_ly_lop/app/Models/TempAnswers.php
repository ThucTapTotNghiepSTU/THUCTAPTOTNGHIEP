<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TempAnswers extends Model
{
    use HasFactory;

    protected $table = 'temp_answers';
    protected $primaryKey = 'temp_answers_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'temp_answers_id',
        'student_id',
        'exam_id',
        'assignment_id',
        'question_id',
        'answer',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($temp_answers) {
            $temp_answers->temp_answers_id = (string) Str::uuid();
        });
    }
}
