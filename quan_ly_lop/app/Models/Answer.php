<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Answer extends Model
{
    use HasFactory;
    protected $table = 'answer';
    protected $primaryKey = 'answer_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'answer_id',
        'submission_id', // Khóa phụ
        'question_title',
        'question_content',
        'question_answer',
    ];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($answer) {
            $answer->answer_id = (string) Str::uuid();
        });
    }
    // Quan hệ với Student
    public function submission()
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }


}
