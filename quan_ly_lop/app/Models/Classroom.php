<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Helpers\StringHelper;

class Classroom extends Model
{
    use HasFactory;
    protected $table = 'classroom';
    protected $primaryKey = 'class_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'class_id',
        'course_id',  // Khóa phụ
        'lecturer_id', //khoá phụ
        'student_classes',//khoá phụ
        'class_code',
        'class_description',
        'class_duration',
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($classroom) {
            $classroom->class_id = (string) Str::uuid();
            $classroom->updateSearchableText();
        });

        static::updating(function ($classroom) {
            $classroom->updateSearchableText();
        });
    }

    protected function updateSearchableText()
    {
        $searchableFields = [
            $this->class_code,
            $this->class_description,
            optional($this->course)->course_name,
            optional($this->lecturer)->fullname
        ];

        $searchableText = collect($searchableFields)
            ->filter()
            ->map(function ($field) {
                return StringHelper::createSearchableText($field);
            })
            ->join(' ');

        $this->searchable_text = $searchableText;
    }

    protected $casts = [
        'class_duration' => 'integer',
    ];

    // Quan hệ với StudentClass
    public function studentClasses()
    {
        return $this->hasMany(StudentClass::class, 'class_id');
    }

    // Quan hệ với Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Quan hệ với Lecturer
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id');
    }
}
