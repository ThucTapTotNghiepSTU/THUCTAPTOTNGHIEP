<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ListQuestion extends Model
{
    use HasFactory;

    protected $table = 'list_question';
    protected $primaryKey = 'list_question_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'list_question_id',
        'course_id', // Khoá phụ
        'topic',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($list_question) {
            // Tạo UUID cho list_question_id
            $list_question->list_question_id = (string) Str::uuid();
        });
    }

    // Quan hệ với Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Quan hệ với Question
    public function questions()
    {
        return $this->hasMany(Question::class, 'list_question_id');
    }
    public function lecturer() {
        return $this->belongsTo(Lecturer::class, 'lecturer_id');
    }
}
