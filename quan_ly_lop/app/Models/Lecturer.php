<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class Lecturer extends Authenticatable
{
    use HasFactory;

    protected $table = 'lecturer';
    protected $primaryKey = 'lecturer_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'lecturer_id',
        'fullname',
        'school_email',
        'personal_email',
        'phone',
        'password',
    ];
    public $timestamps = false;

    protected $hidden = [
        'password',
        'remember_token',
    ];
    // Hàm tạo UUID khi tạo giảng viên mới
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lecturer) {
            if (!$lecturer->lecturer_id) {
                $lecturer->lecturer_id = (string) Str::uuid();
            }
        });
    }

    // Phương thức xác thực: Trả về password để Laravel xác thực
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Phương thức xác định tên của khóa chính
    public function getAuthIdentifierName()
    {
        return 'lecturer_id';
    }

    // Phương thức xác định giá trị khóa chính
    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    // Phương thức xác thực 'remember me'
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    // Phương thức này xác định trường dùng để đăng nhập
    public function username()
    {
        return 'school_email';
    }

    // Quan hệ với bảng Classroom (1 -> N)
    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'lecturer_id');
    }
    public function listQuestions()
    {
        return $this->hasMany(ListQuestion::class, 'lecturer_id');
    }
}
