@extends('templates.template_lecture')

@section('main-content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="lecturer-id" content="{{ Auth::user()->lecturer_id }}">
    <?php
    $lecturer = Auth::user();
        ?>
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <!-- Thẻ thông tin cơ bản -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-tie fa-5x text-secondary"></i>
                        </div>
                        <h5 class="card-title">{{ Auth::guard('lecturer')->user()->fullname }}</h5>
                        <p class="text-muted mb-1">{{ Auth::guard('lecturer')->user()->id }}</p>
                        <p class="text-muted mb-4">Giảng viên</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Thẻ thông tin chi tiết -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Thông tin chi tiết</h5>
                        <form action="{{ route('updateInfo', ['id' => $lecturer->lecturer_id]) }}" method="POST">

                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Họ và tên</h6>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                        name="fullname" value="{{ old('fullname', $lecturer->fullname) }}">
                                    @error('fullname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email trường</h6>
                                </div>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" value="{{ $lecturer->school_email }}" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email cá nhân</h6>
                                </div>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control @error('personal_email') is-invalid @enderror"
                                        name="personal_email"
                                        value="{{ old('personal_email', $lecturer->personal_email) }}">
                                    @error('personal_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Số điện thoại</h6>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" value="{{ old('phone', $lecturer->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Mật khẩu mới</h6>
                                </div>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            color: #333;
            font-weight: 600;
        }

        .form-control:disabled {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }
    </style>
@endsection
