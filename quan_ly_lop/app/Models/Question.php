<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    use HasFactory;
    protected $table = 'question';
    protected $primaryKey = 'question_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'question_id',
        'list_question_id',//Khoá phụ
        'title',
        'content',
        'type',
        'correct_answer',
    ];
    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($question) {
            $question->question_id = (string) Str::uuid();
        });
    }
    public static function getAllowedTypes()
    {
        return ['Trắc nghiệm', 'Tự luận'];
    }

    public function list_question()
    {
        return $this->belongsTo(ListQuestion::class, 'list_question_id');
    }
    public function options()
    {
        return $this->hasMany(Options::class, 'question_id');
    }
    
    public function listQuestion()
    {
        return $this->belongsTo(ListQuestion::class, 'list_question_id', 'list_question_id');
    }
}
