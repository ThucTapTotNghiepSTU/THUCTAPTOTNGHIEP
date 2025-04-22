@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Dashboard Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="fas fa-book-open me-2"></i>Quản lý học tập</h2>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="createButtonDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-plus me-1"></i> Tạo mới
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="createButtonDropdown">
                        <li><a class="dropdown-item" href="/assignments/create"><i class="fas fa-file-alt me-2"></i>Tạo bài tập mới</a></li>
                        <li><a class="dropdown-item" href="/exams/create"><i class="fas fa-clipboard-check me-2"></i>Tạo bài kiểm tra mới</a></li>
                    </ul>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="assignments-tab" data-bs-toggle="tab" data-bs-target="#assignments" type="button" role="tab" aria-controls="assignments" aria-selected="true">
                        <i class="fas fa-file-alt me-2"></i>Bài tập
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="exams-tab" data-bs-toggle="tab" data-bs-target="#exams" type="button" role="tab" aria-controls="exams" aria-selected="false">
                        <i class="fas fa-clipboard-check me-2"></i>Bài kiểm tra
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="myTabContent">
                <!-- Phần Bài tập -->
                <div class="tab-pane fade show active" id="assignments" role="tabpanel" aria-labelledby="assignments-tab">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0">Danh sách bài tập</h5>
                            <a href="/assignments/create" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i> Tạo bài tập mới
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="assignments-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mã bài tập</th>
                                            <th>Tiêu đề</th>
                                            <th>Loại</th>
                                            <th>Thời gian bắt đầu</th>
                                            <th>Thời gian kết thúc</th>
                                            <th>Trạng thái</th>
                                            <th width="150">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Đang tải...</span>
                                                </div>
                                                <p class="mt-2 mb-0">Đang tải dữ liệu...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="assignments-empty" class="text-center py-4 d-none">
                                <i class="fas fa-file-alt fa-3x text-muted"></i>
                                <p class="mt-3 mb-0">Chưa có bài tập nào. Hãy tạo bài tập mới!</p>
                                <a href="/assignments/create" class="btn btn-sm btn-primary mt-3">
                                    <i class="fas fa-plus me-1"></i> Tạo bài tập mới
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Phần Bài kiểm tra -->
                <div class="tab-pane fade" id="exams" role="tabpanel" aria-labelledby="exams-tab">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0">Danh sách bài kiểm tra</h5>
                            <a href="/exams/create" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i> Tạo bài kiểm tra mới
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="exams-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mã bài kiểm tra</th>
                                            <th>Tiêu đề</th>
                                            <th>Loại</th>
                                            <th>Thời gian bắt đầu</th>
                                            <th>Thời gian kết thúc</th>
                                            <th>Trạng thái</th>
                                            <th width="150">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Đang tải...</span>
                                                </div>
                                                <p class="mt-2 mb-0">Đang tải dữ liệu...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="exams-empty" class="text-center py-4 d-none">
                                <i class="fas fa-clipboard-check fa-3x text-muted"></i>
                                <p class="mt-3 mb-0">Chưa có bài kiểm tra nào. Hãy tạo bài kiểm tra mới!</p>
                                <a href="/exams/create" class="btn btn-sm btn-primary mt-3">
                                    <i class="fas fa-plus me-1"></i> Tạo bài kiểm tra mới
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm CSS tùy chỉnh -->
<style>
    .card {
        border-radius: 0.5rem;
        transition: all 0.2s ease-in-out;
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .table th {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .badge {
        font-weight: 500;
        padding: 0.5em 0.8em;
        border-radius: 30px;
    }

    .btn-group .btn {
        border-radius: 0.25rem;
        margin-right: 0.25rem;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .btn-primary {
        background-color: #3490dc;
        border-color: #3490dc;
    }

    .btn-primary:hover {
        background-color: #2779bd;
        border-color: #2779bd;
    }

    .btn-info {
        background-color: #6cb2eb;
        border-color: #6cb2eb;
        color: #fff;
    }

    .btn-info:hover {
        background-color: #4aa0e6;
        border-color: #4aa0e6;
        color: #fff;
    }

    .btn-warning {
        background-color: #ffed4a;
        border-color: #ffed4a;
        color: #333;
    }

    .btn-warning:hover {
        background-color: #ffe924;
        border-color: #ffe924;
        color: #333;
    }

    .bg-success {
        background-color: #38c172 !important;
    }

    .bg-primary {
        background-color: #3490dc !important;
    }

    .bg-secondary {
        background-color: #6c757d !important;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.75rem 1rem;
        font-weight: 500;
        border-bottom: 3px solid transparent;
    }

    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #3490dc;
    }

    .nav-tabs .nav-link.active {
        color: #3490dc;
        background-color: transparent;
        border-bottom: 3px solid #3490dc;
    }

    .dropdown-menu {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 0.5rem;
    }

    .dropdown-item {
        padding: 0.5rem 1.5rem;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Định dạng ngày giờ
        function formatDateTime(dateTimeStr) {
            const dt = new Date(dateTimeStr);
            const day = String(dt.getDate()).padStart(2, '0');
            const month = String(dt.getMonth() + 1).padStart(2, '0');
            const year = dt.getFullYear();
            const hours = String(dt.getHours()).padStart(2, '0');
            const minutes = String(dt.getMinutes()).padStart(2, '0');

            return `${day}/${month}/${year} ${hours}:${minutes}`;
        }

        // Tạo nội dung trạng thái
        function createStatusBadge(status) {
            let badgeClass = '';
            let statusText = '';

            if (status === 'Completed') {
                badgeClass = 'bg-success';
                statusText = 'Hoàn thành';
            } else if (status === 'Processing' || status === 'In Progress') {
                badgeClass = 'bg-primary';
                statusText = 'Đang diễn ra';
            } else if (status === 'Pending') {
                badgeClass = 'bg-secondary';
                statusText = 'Sắp diễn ra';
            } else {
                badgeClass = 'bg-secondary';
                statusText = status;
            }

            return `<span class="badge ${badgeClass}">${statusText}</span>`;
        }

        // Định nghĩa các routes
        const routes = {
            assignment: {
                view: (id) => `/lecturer/assignments/${id}`, // Sử dụng route named
                edit: (id) => `/assignments/${id}/edit`,
                submissions: (id) => `/submissions/index?type=assignment&id=${id}`
            },
            exam: {
                view: (id) => `/lecturer/exams/${id}`, // Sử dụng route named
                edit: (id) => `/exams/${id}/edit`,
                submissions: (id) => `/submissions/index?type=exam&id=${id}`
            }
        };

        // Tạo nút thao tác với routes động
        function createActionButtons(type, id) {
            const typeKey = type === 'assignment' ? 'assignment' : 'exam';

            return `
        <div class="btn-group" role="group">
            <a href="${routes[typeKey].view(id)}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Xem chi tiết">
                <i class="fas fa-eye"></i>
            </a>
            <a href="${routes[typeKey].edit(id)}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Chỉnh sửa">
                <i class="fas fa-edit"></i>
            </a>
            <a href="${routes[typeKey].submissions(id)}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Xem bài nộp">
                <i class="fas fa-tasks"></i>
            </a>
        </div>
    `;
        }

        // Lấy ID của giảng viên hiện tại từ dữ liệu người dùng đăng nhập
        function getCurrentLecturerId() {
            // Cách 1: Nếu ID của giảng viên được truyền từ Laravel vào một biến JS
            if (typeof window.authUser !== 'undefined' && window.authUser.lecturer_id) {
                return window.authUser.lecturer_id;
            }

            // Cách 2: Nếu bạn đã đặt ID trong một phần tử HTML
            const userIdElement = document.getElementById('auth_lecturer_id');
            if (userIdElement && userIdElement.value) {
                return userIdElement.value;
            }

            // Cách 3: Lấy từ meta tag
            const metaLecturerId = document.querySelector('meta[name="lecturer-id"]');
            if (metaLecturerId && metaLecturerId.content) {
                return metaLecturerId.content;
            }

            // Cách 4: Nếu không tìm thấy ID, sử dụng ID mặc định cho testing
            console.warn('Không tìm thấy ID giảng viên, đang sử dụng ID mặc định cho testing.');
            return 'LC001';
        }

        // Lấy ID của giảng viên hiện tại
        const lecturerId = getCurrentLecturerId();
        console.log('Đang sử dụng ID giảng viên:', lecturerId);

        // Lấy CSRF token từ meta tag
        let csrfToken = '';
        const metaCsrf = document.querySelector('meta[name="csrf-token"]');
        if (metaCsrf) {
            csrfToken = metaCsrf.content;
        } else {
            console.warn('CSRF token không tìm thấy. API có thể không hoạt động.');
        }

        // Định nghĩa headers cơ bản
        const headers = {
            'Accept': 'application/json'
        };

        // Thêm CSRF token nếu có
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }

        // Lấy dữ liệu bài tập từ API
        console.log(`Đang gọi API: /api/lecturer-student/assignments?lecturer_id=${lecturerId}`);

        fetch(`/api/lecturer-student/assignments?lecturer_id=${lecturerId}`, {
                method: 'GET',
                headers: headers
            })
            .then(response => {
                console.log('Phản hồi API bài tập:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP status ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Dữ liệu bài tập nhận được:', data);
                const assignmentsTable = document.querySelector('#assignments-table tbody');
                if (!assignmentsTable) {
                    console.error('Không tìm thấy bảng bài tập trong DOM');
                    return;
                }

                // Xử lý dữ liệu bài tập
                let assignments = [];
                if (data.success && data.data) {
                    if (data.data.data && Array.isArray(data.data.data)) {
                        assignments = data.data.data;
                    } else if (Array.isArray(data.data)) {
                        assignments = data.data;
                    }
                }

                if (assignments.length > 0) {
                    assignmentsTable.innerHTML = ''; // Xóa dữ liệu cũ

                    assignments.forEach(assignment => {
                        const row = document.createElement('tr');

                        row.innerHTML = `
                <td><span class="fw-bold">${assignment.assignment_id}</span></td>
                <td>${assignment.title}</td>
                <td><span class="badge bg-light text-dark">${assignment.type}</span></td>
                <td>${formatDateTime(assignment.start_time)}</td>
                <td>${formatDateTime(assignment.end_time)}</td>
                <td>${createStatusBadge(assignment.status)}</td>
                <td>${createActionButtons('assignment', assignment.assignment_id)}</td>
            `;

                        assignmentsTable.appendChild(row);
                    });

                    // Khởi tạo tooltips
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.map(function(tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    }

                    // Ẩn thông báo trống
                    const emptyElement = document.querySelector('#assignments-empty');
                    if (emptyElement) {
                        emptyElement.classList.add('d-none');
                    }
                } else {
                    // Hiển thị thông báo nếu không có dữ liệu
                    const emptyElement = document.querySelector('#assignments-empty');
                    if (emptyElement) {
                        emptyElement.classList.remove('d-none');
                    }
                    assignmentsTable.innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Lỗi khi gọi API bài tập:', error);
                const assignmentsTable = document.querySelector('#assignments-table tbody');
                if (assignmentsTable) {
                    assignmentsTable.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-warning fa-2x mb-3"></i>
                    <p class="mb-0">Không thể tải dữ liệu bài tập. Vui lòng thử lại sau.</p>
                    <p class="text-danger small">Lỗi: ${error.message}</p>
                    <button class="btn btn-sm btn-outline-primary mt-3" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-1"></i> Tải lại
                    </button>
                </td>
            </tr>
        `;
                }
            });

        // Lấy dữ liệu bài kiểm tra từ API
        console.log(`Đang gọi API: /api/lecturer-student/exams?lecturer_id=${lecturerId}`);

        fetch(`/api/lecturer-student/exams?lecturer_id=${lecturerId}`, {
                method: 'GET',
                headers: headers
            })
            .then(response => {
                console.log('Phản hồi API bài kiểm tra:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP status ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Dữ liệu bài kiểm tra nhận được:', data);
                const examsTable = document.querySelector('#exams-table tbody');
                if (!examsTable) {
                    console.error('Không tìm thấy bảng bài kiểm tra trong DOM');
                    return;
                }

                // Xử lý dữ liệu bài kiểm tra
                let exams = [];
                if (data.success && data.data) {
                    if (data.data.data && Array.isArray(data.data.data)) {
                        exams = data.data.data;
                    } else if (Array.isArray(data.data)) {
                        exams = data.data;
                    }
                }

                if (exams.length > 0) {
                    examsTable.innerHTML = ''; // Xóa dữ liệu cũ

                    exams.forEach(exam => {
                        const row = document.createElement('tr');

                        row.innerHTML = `
                <td><span class="fw-bold">${exam.exam_id}</span></td>
                <td>${exam.title}</td>
                <td><span class="badge bg-light text-dark">${exam.type}</span></td>
                <td>${formatDateTime(exam.start_time)}</td>
                <td>${formatDateTime(exam.end_time)}</td>
                <td>${createStatusBadge(exam.status)}</td>
                <td>${createActionButtons('exam', exam.exam_id)}</td>
            `;

                        examsTable.appendChild(row);
                    });

                    // Khởi tạo tooltips
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.map(function(tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    }

                    // Ẩn thông báo trống
                    const emptyElement = document.querySelector('#exams-empty');
                    if (emptyElement) {
                        emptyElement.classList.add('d-none');
                    }
                } else {
                    // Hiển thị thông báo nếu không có dữ liệu
                    const emptyElement = document.querySelector('#exams-empty');
                    if (emptyElement) {
                        emptyElement.classList.remove('d-none');
                    }
                    examsTable.innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Lỗi khi gọi API bài kiểm tra:', error);
                const examsTable = document.querySelector('#exams-table tbody');
                if (examsTable) {
                    examsTable.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-warning fa-2x mb-3"></i>
                    <p class="mb-0">Không thể tải dữ liệu bài kiểm tra. Vui lòng thử lại sau.</p>
                    <p class="text-danger small">Lỗi: ${error.message}</p>
                    <button class="btn btn-sm btn-outline-primary mt-3" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-1"></i> Tải lại
                    </button>
                </td>
            </tr>
        `;
                }
            });

        // Hiển thị tab được chọn dựa trên URL hash nếu có
        const hash = window.location.hash;
        if (hash) {
            const tab = document.querySelector(`a[href="${hash}"]`);
            if (tab) {
                tab.click();
            }
        }
    });
</script>
@endsection