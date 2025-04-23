@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Chi tiết bài kiểm tra</h3>
                    <a href="{{ route('lecturer.dashboard') }}" class="btn btn-sm btn-secondary">Quay lại</a>
                </div>
                <div class="card-body">
                    <div id="exam-detail">
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
    fetchExamDetail('{{ $examId }}');
});

function fetchExamDetail(examId) {
    fetch(`/api/lecturer-student/exams/${examId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                displayExamDetail(data.data);
            } else {
                document.getElementById('exam-detail').innerHTML = `<div class="alert alert-danger">Error loading data</div>`;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            document.getElementById('exam-detail').innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        });
}

function displayExamDetail(data) {
    const exam = data.exam;
    const submissions = data.submissions;
    
    let html = `
        <div class="mb-4">
            <h4>${exam.title}</h4>
            <p>${exam.content}</p>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> ${exam.exam_id}</p>
                    <p><strong>Loại:</strong> ${exam.type}</p>
                    <p><strong>Trạng thái:</strong> ${exam.status}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Thời gian bắt đầu:</strong> ${new Date(exam.start_time).toLocaleString()}</p>
                    <p><strong>Thời gian kết thúc:</strong> ${new Date(exam.end_time).toLocaleString()}</p>
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
    
    exam.sub_list.sub_list_questions.forEach(item => {
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
                            <th>Điểm</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>`;
    
    submissions.forEach(sub => {
        html += `
            <tr>
                <td>${sub.submission_id}</td>
                <td>${sub.student.full_name} (${sub.student.student_code})</td>
                <td>${new Date(sub.created_at).toLocaleString()}</td>
                <td>${sub.temporary_score}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="showAnswers('${sub.submission_id}')">Xem chi tiết</button>
                </td>
            </tr>
            <tr id="answers-${sub.submission_id}" style="display:none;">
                <td colspan="5">
                    <div class="p-3">
                        <h6>Chi tiết câu trả lời:</h6>
                        <ul class="list-group">`;
        
        sub.answers.forEach(ans => {
            html += `
                <li class="list-group-item">
                    <strong>${ans.question_title}:</strong> ${ans.question_content}<br>
                    <strong>Trả lời:</strong> ${ans.question_answer}
                </li>`;
        });
        
        html += `
                        </ul>
                    </div>
                </td>
            </tr>`;
    });
    
    html += `
                    </tbody>
                </table>
            </div>
        </div>`;
    
    document.getElementById('exam-detail').innerHTML = html;
}

function showAnswers(submissionId) {
    const answersRow = document.getElementById(`answers-${submissionId}`);
    if (answersRow.style.display === 'none') {
        answersRow.style.display = 'table-row';
    } else {
        answersRow.style.display = 'none';
    }
}
</script>
@endsection