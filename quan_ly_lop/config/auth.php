<?php
return [
    'defaults' => [
        'guard' => 'students',
        'passwords' => 'students',
    ],
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'students' => [
            'driver' => 'session',
            'provider' => 'students',
        ],
        'lecturer' => [
            'driver' => 'session',
            'provider' => 'lecturers',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, 
        ],
        'students' => [
            'driver' => 'eloquent',
            'model' => App\Models\Student::class,
        ],
        'lecturers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Lecturer::class,
        ],
    ],


    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'students' => [
            'provider' => 'students',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
    'password_timeout' => 10800,

];
