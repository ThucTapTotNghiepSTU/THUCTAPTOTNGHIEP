@extends('templates.template_normal')
@section('main-content')
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @auth
            <!-- Header Profile -->
            <div class="profile-header card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-circle">
                                <i class="fas fa-user-graduate fa-3x text-white"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">{{ Auth::user()->full_name }}</h4>
                            <p class="text-light mb-0">
                                <i class="fas fa-id-card me-2"></i>{{ Auth::user()->student_code }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê -->
            <div class="stats-row row g-4 mb-4" id="statistical">
            </div>

            <!-- Thanh tìm kiếm và lọc -->
            <div class="search-filter-card card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="search-box">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="searchInput" class="form-control search-input"
                                    placeholder="Tìm kiếm lớp học...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="statusSelect" class="form-select custom-select">
                                <option value="">Trạng thái</option>
                                <option value="Đang học">Đang học</option>
                                <option value="Đã hoàn thành">Đã hoàn thành</option>
                                <option value="Tất cả">Tất cả</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="sortSelect" class="form-select custom-select">
                                <option value="">Sắp xếp theo</option>
                                <option value="newest">Mới nhất</option>
                                <option value="oldest">Cũ nhất</option>
                                <option value="name_asc">Tên A-Z</option>
                                <option value="name_desc">Tên Z-A</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Danh sách lớp -->
            <div class="classes-section">
                <h4 class="section-title mb-4">Lớp của tôi</h4>
                <div class="row g-4" id="dynamic-classes">
                    @foreach($classes as $class)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="class-card card h-100">
                                <div class="class-card-header">
                                    <img src="{{ $class['image'] }}" class="class-image" alt="{{ $class['title'] }}">
                                    <div class="card-img-overlay">
                                        <span class="badge status-badge
                                            {{ $class['status'] === 'Đang học' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $class['status'] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <p class="card-author mb-0">
                                            <i class="fas fa-chalkboard-teacher me-2"></i>{{ $class['author'] }}
                                        </p>
                                    </div>
                                    <h5 class="card-title">{{ $class['title'] }}</h5>
                                    <p class="card-text text-muted">{{ $class['description'] }}</p>

                                    <div class="class-info">
                                        <div class="info-item">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            <span>{{ $class['date'] }}</span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-code me-2"></i>
                                            <span>{{ $class['class_code'] }}</span>
                                        </div>
                                    </div>
                                    @if($class['final_score'] !== null)
                                        <div class="mt-2">
                                            <span class="badge bg-info">
                                                <i class="fas fa-star me-1"></i> Điểm: {{ $class['final_score'] }}/10
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bài kiểm tra -->
            <div class="tests-section mt-5">
                <h4 class="section-title mb-4">Bài kiểm tra</h4>
                <div class="row g-4" id="dynamic-tests">
                    <!-- Danh sách bài kiểm tra sẽ được render ở đây -->
                </div>
            </div>
        @endauth
    </div>
    @auth
        <meta name="student_id" content="{{ Auth::user()->student_id }}">
    @endauth

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const studentId = document.querySelector('meta[name="student_id"]').getAttribute('content');
            const token = localStorage.getItem('token');

            fetch(`/api/classrooms/student-classes/${studentId}`, {
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
                    statistical(data);
                    renderClasses(data);

                    // Gắn lại dữ liệu để lọc
                    window.allClasses = data;
                })
                .catch(error => {
                    console.log(error);
                    document.getElementById('dynamic-classes').innerHTML =
                        '<p class="text-danger">Lỗi khi tải lớp học.</p>';
                    document.getElementById('statistical').innerHTML =
                        '<p class="text-danger">Lỗi khi tải thông tin.</p>';
                });

            const classId = 'ID_LỚP_HỌC'; // Thay bằng ID lớp học hiện tại

            fetch(`/getAllExamsAndAssignments/{studentId}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Lỗi khi gọi API bài kiểm tra');
                    }
                    return response.json();
                })
                .then(data => {
                    renderTests(data);
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('dynamic-tests').innerHTML =
                        '<p class="text-danger">Lỗi khi tải bài kiểm tra.</p>';
                });
        });

        const searchInput = document.getElementById('searchInput');
        const statusSelect = document.getElementById('statusSelect');
        const sortSelect = document.getElementById('sortSelect');

        searchInput.addEventListener('input', applyFilters);
        statusSelect.addEventListener('change', applyFilters);
        sortSelect.addEventListener('change', applyFilters);
        function applyFilters() {
            const searchValue = searchInput.value.toLowerCase().trim();
            const statusValue = statusSelect.value;
            const sortValue = sortSelect.value;

            let filteredData = window.allClasses || [];

            // Tìm kiếm theo tên lớp
            if (searchValue) {
                filteredData = filteredData.filter(item =>
                    item.course_name && item.course_name.toLowerCase().includes(searchValue)
                );
            }

            // Lọc theo trạng thái
            if (statusValue && statusValue !== 'Tất cả') {
                filteredData = filteredData.filter(item =>
                    item.status === statusValue || item.status === (statusValue === 'Đang học' ? 'Active' : 'Drop')
                );
            }

            // Sắp xếp
            filteredData.sort((a, b) => {
                if (sortValue === 'newest') {
                    return new Date(b.created_at) - new Date(a.created_at);
                } else if (sortValue === 'oldest') {
                    return new Date(a.created_at) - new Date(b.created_at);
                } else if (sortValue === 'name_asc') {
                    return a.course_name.localeCompare(b.course_name);
                } else if (sortValue === 'name_desc') {
                    return b.course_name.localeCompare(a.course_name);
                }
                return 0;
            });

            renderClasses(filteredData);
        }
        function statistical(data) {
            const container = document.getElementById('statistical');
            if (data.length === 0) {
                container.innerHTML = '<p class="text-muted">Không có lớp.</p>';
                return;
            }
            let activeClasses = 0;
            let completedClasses = 0;
            let totalHours = 0;

            data.forEach(classItem => {
                if (classItem.status === 'Active' || classItem.status === 'Đang diễn ra') {
                    activeClasses++;
                } else if (classItem.status === 'Drop') {
                    completedClasses++;
                }

                totalHours += parseFloat(classItem.class_duration || 0);
            });

            const statisticalHTML = `
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon bg-primary-soft">
                                    <i class="fas fa-book-open text-primary"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>${activeClasses}</h3>
                                    <p>Lớp đang học</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon bg-success-soft">
                                    <i class="fas fa-graduation-cap text-success"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>${completedClasses}</h3>
                                    <p>Đã hoàn thành</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon bg-info-soft">
                                    <i class="fas fa-clock text-info"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>${totalHours}</h3>
                                    <p>Giờ học</p>
                                </div>
                            </div>
                        </div>
                    `;
            document.getElementById('statistical').innerHTML = statisticalHTML;
        }
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
                                        <img src="${classItem.image || 'images/header_image/default-class.jpg'}" class="class-image" alt="${classItem.course_name}">
                                        <div class="card-img-overlay">
                                            <span class="badge status-badge
                                                ${classItem.status === 'Đang diễn ra' || classItem.status === 'Active' ? 'bg-success' :
                        classItem.status === 'Drop' ? 'bg-secondary' : 'bg-warning'}">
                                                ${classItem.status || 'Không rõ'}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <p class="card-author mb-0">
                                                <i class="fas fa-chalkboard-teacher me-2"></i>${classItem.lecturer_name || 'Không rõ'}
                                            </p>
                                        </div>
                                        <h5 class="card-title">${classItem.course_name || 'Tên lớp'}</h5>
                                        <p class="card-text text-muted">${classItem.class_description || 'Không có mô tả'}</p>

                                        <div class="class-info">
                                            <div class="info-item">
                                                <i class="fas fa-users me-2"></i>
                                                <span>${classItem.total_students || 0}</span>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-calendar-alt me-2"></i>
                                                <span>${classItem.class_duration || 'N/A'}</span>
                                            </div>
                                        </div>
                                        ${classItem.course_score != null ? `
                                            <div class="mt-2">
                                                <span class="badge bg-info">
                                                    <i class="fas fa-star me-1"></i> Điểm: ${classItem.course_score}/10
                                                </span>
                                            </div>
                                        ` : ''}

                                        ${classItem.status !== 'Drop' ? `
                                                <div class="mt-3">
                                                    <button class="btn btn-primary w-100 join-button"
                                                        data-course-id="${classItem.course_id}"
                                                        data-lecturer-id="${classItem.lecturer_id}"
                                                        data-class-id="${classItem.class_id}">
                                                        <i class="fas fa-sign-in-alt me-2"></i>chi tiet
                                                    </button>
                                                </div>
                                            ` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
            });
            container.innerHTML = html;
            attachJoinHandlers();
        }
        function attachJoinHandlers() {
            const joinButtons = document.querySelectorAll('.join-button');
            joinButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const courseId = this.getAttribute('data-course-id');
                    const lecturerId = this.getAttribute('data-lecturer-id');
                    const classId = this.getAttribute('data-class-id');
                    const listId = {
                        course_id: courseId,
                        lecturer_id: lecturerId,
                        class_id: classId
                    };
                    localStorage.setItem("list_id_course_lecturer", JSON.stringify(listId));

                    // Tuỳ chọn: điều hướng sang trang
                    window.location.href = "/classDetail";
                });
            });
        }
        function renderTests(data) {
            const container = document.getElementById('dynamic-tests');
            if (data.length === 0) {
                container.innerHTML = '<p class="text-muted">Không có bài kiểm tra nào.</p>';
                return;
            }

            let html = '';
            data.forEach(test => {
                html += `
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="test-card card h-100">
                            <div class="card-body">
                                <h5 class="card-title">${test.title}</h5>
                                <p class="card-text text-muted">${test.description || 'Không có mô tả'}</p>
                                <div class="test-info">
                                    <div class="info-item">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <span>${test.date || 'N/A'}</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-clock me-2"></i>
                                        <span>${test.duration || 'N/A'} phút</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/tests/${test.id}" class="btn btn-primary w-100">
                                        <i class="fas fa-pen me-2"></i>Làm bài
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }
    </script>
    <style>
        /* Profile Header */
        .profile-header {
            background: linear-gradient(135deg, rgb(16, 42, 80) 0%, #6610f2 100%);
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

        /* Test Cards */
        .test-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
            transition: transform 0.3s ease;
        }

        .test-card:hover {
            transform: translateY(-5px);
        }

        .test-info {
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
    </style>
@endsection
