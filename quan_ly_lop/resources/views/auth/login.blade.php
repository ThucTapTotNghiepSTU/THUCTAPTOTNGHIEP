@extends('templates.template_normal')

@section('main-content')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-card">
                    <div class="auth-header">
                        <i class="bi bi-person-circle me-2"></i>Đăng nhập
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="auth-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4">
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

                            <div class="mb-4">
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

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                            </div>

                            <button type="submit" class="btn btn-primary auth-btn w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
                            </button>
                        </form>
                    </div>

                    <div class="auth-footer">
                        <p class="mb-0">
                            Chưa có tài khoản?
                            <a href="{{ route('register') }}" class="auth-link">Đăng ký ngay</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
