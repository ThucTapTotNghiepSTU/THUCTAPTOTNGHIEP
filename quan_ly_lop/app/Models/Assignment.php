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
        'sub_list_id',
        'title',
        'content',
        'type',
        'isSimultaneous',
        'show_result',
        'start_time',
        'end_time',
        'status',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($assignment) {
            if (!$assignment->assignment_id) {
                $assignment->assignment_id = (string) Str::uuid();
            }
        });
    }

    public static function getAllowedStatuses()
    {
        return ['Pending', 'Processing', 'Completed'];
    }

    public static function getAllowedTypes()
    {
        return ['Trắc nghiệm', 'Tự luận'];
    }
    public function subList()
    {
        return $this->belongsTo(SubList::class, 'sub_list_id');
    }
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
