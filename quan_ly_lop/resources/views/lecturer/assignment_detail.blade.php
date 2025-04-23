@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Chi tiết bài tập</h3>
                    <a href="{{ route('lecturer.dashboard') }}" class="btn btn-sm btn-secondary">Quay lại</a>
                </div>
                <div class="card-body">
                    <div id="assignment-detail">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const assignmentId = getCurrentAssignmentId();
        fetchAssignmentDetail(assignmentId);
    });

    function getCurrentAssignmentId() {
        // Cách 1: Nếu ID của assignment được truyền từ Laravel vào một biến JS
        if (typeof window.currentAssignment !== 'undefined' && window.currentAssignment.id) {
            return window.currentAssignment.id;
        }

        // Cách 2: Nếu bạn đã đặt ID trong một phần tử HTML (thường được render từ Blade template)
        const assignmentIdElement = document.getElementById('assignment_id');
        if (assignmentIdElement && assignmentIdElement.value) {
            return assignmentIdElement.value;
        }

        // Cách 3: Lấy từ meta tag (cách phổ biến để truyền dữ liệu từ backend sang frontend)
        const metaAssignmentId = document.querySelector('meta[name="assignment-id"]');
        if (metaAssignmentId && metaAssignmentId.content) {
            return metaAssignmentId.content;
        }

        // Cách 4: Lấy từ URL parameter (nếu ID được đặt trong URL)
        const urlParams = new URLSearchParams(window.location.search);
        const paramAssignmentId = urlParams.get('assignment_id');
        if (paramAssignmentId) {
            return paramAssignmentId;
        }

        // Cách 5: Nếu không tìm thấy ID, sử dụng ID mặc định cho testing
        console.warn('Không tìm thấy ID bài tập, đang sử dụng ID mặc định cho testing.');
        return 'AS001';
    }


    function fetchAssignmentDetail(assignmentId) {
        fetch(`/api/lecturer-student/assignments/${assignmentId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    displayAssignmentDetail(data.data);
                } else {
                    document.getElementById('assignment-detail').innerHTML = `<div class="alert alert-danger">Error loading data</div>`;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById('assignment-detail').innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
            });
    }

    function displayAssignmentDetail(data) {
        const assignment = data.assignment;
        const submissions = data.submissions;

        let html = `
        <div class="mb-4">
            <h4>${assignment.title}</h4>
            <p>${assignment.content}</p>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> ${assignment.assignment_id}</p>
                    <p><strong>Loại:</strong> ${assignment.type}</p>
                    <p><strong>Trạng thái:</strong> ${assignment.status}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Thời gian bắt đầu:</strong> ${new Date(assignment.start_time).toLocaleString()}</p>
                    <p><strong>Thời gian kết thúc:</strong> ${new Date(assignment.end_time).toLocaleString()}</p>
                    <p><strong>Số lượng bài nộp:</strong> ${data.submission_count}</p>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h5>Danh sách câu hỏi</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Nội dung</th>
                            <th>Loại</th>
                        </tr>
                    </thead>
                    <tbody>`;

        assignment.sub_list.sub_list_questions.forEach(item => {
            const q = item.question;
            html += `
            <tr>
                <td>${q.question_id}</td>
                <td>${q.title}</td>
                <td>${q.content}</td>
                <td>${q.type}</td>
            </tr>`;
        });

        html += `
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-4">
            <h5>Danh sách bài nộp</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Sinh viên</th>
                            <th>Thời gian nộp</th>
                            <th>File nộp</th>
                            <th>Điểm</th>
                        </tr>
                    </thead>
                    <tbody>`;

        submissions.forEach(sub => {
            html += `
            <tr>
                <td>${sub.submission_id}</td>
                <td>${sub.student.full_name} (${sub.student.student_code})</td>
                <td>${new Date(sub.created_at).toLocaleString()}</td>
                <td>
                    ${sub.answer_file ? 
                    `<a href="#" class="btn btn-sm btn-outline-primary">Tải ${sub.answer_file}</a>` : 
                    'Không có file'}
                </td>
                <td>${sub.temporary_score}</td>
            </tr>`;
        });

        html += `
                    </tbody>
                </table>
            </div>
        </div>`;

        document.getElementById('assignment-detail').innerHTML = html;
    }
</script>
@endsection