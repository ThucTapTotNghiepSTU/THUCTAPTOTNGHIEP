<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubList extends Model
{
    use HasFactory;
    protected $table = 'sub_list';
    protected $primaryKey = 'sub_list_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sub_list_id',
        'title',
        'isShuffle',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sub_list) {
            $sub_list->sub_list_id = (string) Str::uuid();
        });
    }
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'sub_list_id');
    }
    public function exams()
    {
        return $this->hasMany(Exam::class, 'sub_list_id');
    }
    public function subListQuestions()
    {
        return $this->hasMany(SubListQuestion::class, 'sub_list_id', 'sub_list_id');
    }
    public function getListQuestionIdAttribute()
    {
        $listQuestionIds = $this->questions()
            ->pluck('list_question_id')
            ->unique();

        //nếu không có câu hỏi
        if ($listQuestionIds->isEmpty()) {
            return null;
        }
        if ($listQuestionIds->count() > 1) {
            throw new \Exception('Lỗi ngoại lệ! Các mã đề hiện tại thuộc về nhiều hơn 1 bộ câu hỏi');
        }
        //nếu có thì sẽ trả về giá trị listquestion đầu tiên vì mặc định chỉ có 1 list question liên quan đến mã đề này.
        return $listQuestionIds->first();
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'sub_list_question', 'sub_list_id', 'question_id');
    }
}
