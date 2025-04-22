@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="auth-card">
                    <div class="auth-header">
                        <i class="bi bi-person-plus-fill me-2"></i>Đăng ký tài khoản
                    </div>

                    <div class="auth-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-badge"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control left-border @error('student_code') is-invalid @enderror"
                                           name="student_code"
                                           placeholder="Mã số sinh viên"
                                           value="{{ old('student_code') }}"
                                           required>
                                </div>
                                @error('student_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control left-border @error('full_name') is-invalid @enderror"
                                           name="full_name"
                                           placeholder="Họ và tên"
                                           value="{{ old('full_name') }}"
                                           required>
                                </div>
                                @error('full_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email"
                                           class="form-control left-border @error('school_email') is-invalid @enderror"
                                           name="school_email"
                                           placeholder="Email trường"
                                           value="{{ old('school_email') }}"
                                           required>
                                </div>
                                @error('school_email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-phone"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control left-border @error('phone') is-invalid @enderror"
                                           name="phone"
                                           placeholder="Số điện thoại"
                                           value="{{ old('phone') }}"
                                           required>
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control left-border @error('password') is-invalid @enderror"
                                           name="password"
                                           placeholder="Mật khẩu"
                                           required>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control left-border"
                                           name="password_confirmation"
                                           placeholder="Xác nhận mật khẩu"
                                           required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary auth-btn w-100">
                                <i class="bi bi-person-plus me-2"></i>Đăng ký
                            </button>
                        </form>
                    </div>

                    <div class="auth-footer">
                        <p class="mb-0">
                            Đã có tài khoản?
                            <a href="{{ route('Showlogin') }}" class="auth-link">Đăng nhập</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
