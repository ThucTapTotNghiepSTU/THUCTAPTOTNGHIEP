<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;


class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignment';
    protected $primaryKey = 'assignment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'assignment_id',
        'sub_list_id',  // Khóa phụ
        'title',
        'content',
        'type',
        'isSimultaneous',
        'start_time',
        'end_time',
        'show_result',
        'status',
        'created_by'
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($assignment) {
            $assignment->assignment_id = (string) Str::uuid();
        });
    }

    public static function getAllowedStatuses()
    {
        return ['Pending', 'Processing', 'Completed'];
    }

    // Danh sách kiểu bài thi hợp lệ
    public static function getAllowedTypes()
    {
        return ['Trắc nghiệm', 'Tự luận'];
    }

    // Quan hệ với Course
    public function sublist()
    {
        return $this->belongsTo(SubList::class, 'sub_list_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id'); // 'course_id' là tên cột khoá ngoại liên kết đến bảng 'course'
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'assignment_class');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

}
