<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubListQuestion extends Model
{
    use HasFactory;
    protected $table = 'sub_list_question';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sub_list_id', // Khóa ngoại
        'question_id',  // Khóa ngoại
    ];
    public $timestamps = false;

    // Quan hệ với bảng `sub_list`
    public function sub_list()
    {
        return $this->belongsTo(SubList::class, 'sub_list_id');
    }

    // Quan hệ với bảng `question`
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
