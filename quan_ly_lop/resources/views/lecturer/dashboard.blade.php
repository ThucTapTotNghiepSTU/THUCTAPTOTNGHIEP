@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Giảng viên Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h4>Bài kiểm tra và Bài tập</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-primary text-white">Bài kiểm tra</div>
                                    <div class="card-body">
                                        <h5>Thi giữa kỳ Lập trình cơ bản</h5>
                                        <p>ID: EX001</p>
                                        <a href="{{ route('lecturer.exam.detail', 'EX001') }}" class="btn btn-sm btn-primary">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-success text-white">Bài tập</div>
                                    <div class="card-body">
                                        <h5>Bài tập 1 - Lập trình cơ bản</h5>
                                        <p>ID: AS001</p>
                                        <a href="{{ route('lecturer.assignment.detail', 'AS001') }}" class="btn btn-sm btn-success">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection