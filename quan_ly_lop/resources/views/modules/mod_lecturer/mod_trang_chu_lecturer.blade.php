@extends('templates.template_lecture')
@section('main-content')
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @auth
            <!-- Thông tin người dùng -->
            <div class="profile-header card mb-4 mx-auto">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-circle">
                                <i class="fas fa-user-graduate fa-3x text-white"></i>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-light mb-0">
                                <i class="fas fa-id-card me-2"></i>@if(Auth::guard('lecturer')->check())
                                Xin chào giảng viên {{ Auth::guard('lecturer')->user()->fullname }}
                            @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thanh tìm kiếm và lọc -->
            <div class="search-filter-container mb-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control search-input" placeholder="Tìm kiếm lớp học..."
                                id="searchInput">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select">
                            <option selected>Tất cả lớp học</option>
                            <option>Đang diễn ra</option>
                            <option>Sắp khai giảng</option>
                            <option>Đã kết thúc</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Thống kê lớp học -->
            <div class="d-flex flex-column justify-content-start ">
                <h3 class="section-title mb-4">Lớp học hiện có</h3>
                <div class="row justify-content-start" id="dynamic-classes">
                </div>
            </div>
        @else
            <!-- Trang chào mừng cho khách -->
            <div class="welcome-container text-center py-5">
                <div class="welcome-icon mb-4">
                    <i class="fas fa-graduation-cap fa-4x text-primary"></i>
                </div>
                <h2 class="welcome-title mb-3">Chào mừng đến với hệ thống quản lý lớp học</h2>
                <p class="welcome-text mb-4">Đăng nhập để trải nghiệm đầy đủ các tính năng của hệ thống.</p>
                <div class="welcome-buttons">
                    <a href="{{ route('Showlogin') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                    </a>
                    <!--                     <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                                                        <i class="fas fa-user-plus me-2"></i>Đăng ký
                                                    </a> -->
                </div>
            </div>
        @endauth
    </div>
    @auth
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="lecturer-id" content="{{ Auth::user()->lecturer_id }}">
    @endauth

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lecturerId = document.querySelector('meta[name="lecturer-id"]').getAttribute('content');
            const token = localStorage.getItem('token');
            fetch(`/api/lecturers/${lecturerId}/classrooms`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Lỗi khi gọi API');
                    }
                    return response.json();
                })
                .then(data => {
                    // Nếu bạn trả về object {lecturer_id, fullname, classrooms: [...]}
                    renderClasses(data.classrooms || []);
                    window.allClasses = data.classrooms || [];
                    searchBox();
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('dynamic-classes').innerHTML =
                        '<p class="text-danger">Lỗi khi tải lớp học.</p>';
                });
        });

        function renderClasses(data) {
            const container = document.getElementById('dynamic-classes');
            if (data.length === 0) {
                container.innerHTML = '<p class="text-muted">Không có lớp học nào.</p>';
                return;
            }

            let html = '';
            data.forEach(classItem => {
                html += `
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="class-card card h-100">
                                <div class="class-card-header">
                                    <img src="${classItem.image || 'images/header_image/default-class.jpg'}" class="class-image" alt="${classItem.course?.course_name || 'Lớp học'}">
                                </div>

                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <p class="card-author mb-0">
                                            <i class="fas fa-chalkboard-teacher me-2"></i>Bạn
                                        </p>
                                    </div>
                                    <h5 class="card-title">${classItem.course?.course_name || 'Tên lớp'}</h5>
                                    <p class="card-text text-muted">${classItem.class_description || 'Không có mô tả'}</p>

                                    <div class="class-info">
                                        <div class="info-item">
                                            <i class="fas fa-users me-2"></i>
                                            <span>${classItem.studentClasses?.length || 0}</span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            <span>${classItem.class_duration || 'N/A'}</span>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <button class="btn btn-outline-primary w-100 join-button"
                                            data-class-id="${classItem.class_id}">
                                            <i class="fas fa-eye me-2"></i>Xem chi tiết
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
            });

            container.innerHTML = html;
            attachJoinHandlers();
        }

        function attachJoinHandlers() {
            const joinButtons = document.querySelectorAll('.join-button'); // Tìm tất cả nút có class 'join-button'

            joinButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Lấy thông tin từ thuộc tính data-* của nút
                    const courseId = this.getAttribute('data-course-id');
                    const lecturerId = this.getAttribute('data-lecturer-id');
                    const classId = this.getAttribute('data-class-id');

                    // Tạo một object chứa các ID này
                    const listId = {
                        course_id: courseId,
                        lecturer_id: lecturerId,
                        class_id: classId
                    };

                    // Lưu object đó vào localStorage dưới tên "list_id_course_lecturer"
                    localStorage.setItem("list_id_course_lecturer", JSON.stringify(listId));

                    // Chuyển hướng đến trang chi tiết lớp học
                    window.location.href = "/classDetail";
                });
            });
        }

        function removeVietnameseTones(str) {
            return str.normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '') // Remove diacritics
                .replace(/đ/g, 'd').replace(/Đ/g, 'D');
        }
        function searchBox() {
            document.getElementById('searchInput').addEventListener('input', function () {
                const keyword = removeVietnameseTones(this.value.toLowerCase());
                const filtered = window.allClasses.filter(classItem =>
                    removeVietnameseTones(classItem.course_name.toLowerCase()).includes(keyword)
                );
                renderClasses(filtered);
            });
        }

    </script>
    <style>
        /* Profile Header */
        .profile-header {
            background: linear-gradient(135deg, rgb(6, 63, 41) 0%,rgb(74, 201, 105) 100%);
            border: none;
            border-radius: 15px;
            color: white;
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .bg-primary-soft {
            background: rgba(0, 97, 242, 0.1);
        }

        .bg-success-soft {
            background: rgba(40, 167, 69, 0.1);
        }

        .bg-info-soft {
            background: rgba(23, 162, 184, 0.1);
        }

        .bg-warning-soft {
            background: rgba(255, 193, 7, 0.1);
        }

        .stat-info h3 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: #333;
        }

        .stat-info p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* Search and Filter */
        .search-filter-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
        }

        .search-box {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .search-input {
            padding-left: 45px;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
        }

        .custom-select {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
        }

        /* Class Cards */
        .class-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
            transition: transform 0.3s ease;
        }

        .class-card:hover {
            transform: translateY(-5px);
        }

        .class-card-header {
            position: relative;
        }

        .class-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 8px 15px;
            border-radius: 20px;
            color: white;
            font-size: 12px;
        }

        .badge-success {
            background: #28a745;
        }

        .badge-secondary {
            background: #6c757d;
        }

        .class-card-body {
            padding: 20px;
        }

        .class-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .class-description {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .class-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 14px;
        }

        .progress-section {
            margin-bottom: 15px;
        }

        .progress {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        .progress-bar {
            background: linear-gradient(135deg, #0061f2 0%, #6610f2 100%);
            border-radius: 4px;
        }

        .progress-text {
            font-size: 12px;
            color: #6c757d;
        }

        .score-section {
            margin-bottom: 15px;
        }

        .score-badge {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            padding: 10px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .score-value {
            font-weight: 600;
            font-size: 16px;
        }

        .class-actions {
            margin-top: 20px;
        }

        .btn-enter,
        .btn-review {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            font-weight: 500;
        }

        .section-title {
            color: #333;
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, #0061f2 0%, #6610f2 100%);
            border-radius: 3px;
        }
    </style>
@endsection
