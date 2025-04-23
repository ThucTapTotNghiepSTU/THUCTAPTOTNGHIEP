@extends('layouts.app')

@section('content')
    <style>
        /* Override container width to ensure full display */
        .container {
            max-width: 100% !important;
            padding-left: 30px !important;
            padding-right: 30px !important;
        }

        /* Ensure announcements display at full width */
        .timeline .card {
            width: 100% !important;
        }

        /* Improve responsive behavior */
        @media (min-width: 768px) {
            .container {
                max-width: 95% !important;
            }

            .col-md-10 {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
        }

        /* Fix tab content spacing */
        .tab-pane {
            padding: 15px 0;
        }

        /* Improve button spacing */
        .d-flex .btn {
            margin: 0 5px;
        }

        /* Fix modal width */
        .modal-lg {
            max-width: 90% !important;
        }
        .class-code-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            max-width: 300px;
        }
        
        .class-code-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            color: #666;
            font-size: 14px;
        }
        
        .class-code-value {
            font-size: 24px;
            font-weight: 500;
            color: #0d6efd;
            margin: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .copy-button {
            background: transparent;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 4px;
        }
        
        .copy-button:hover {
            color: #0d6efd;
        }
        
        /* Override any container constraints */
        .container {
            max-width: 1200px !important;
        }
    </style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ $classroom->name }}</h3>
                    <div class="class-code-card">
                        <div class="class-code-header">
                            <span>Mã lớp</span>
                            <button class="copy-button" onclick="copyToClipboard('{{ $classroom->code }}')">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                        <div class="class-code-value">
                            <span style="color: #0d6efd;">{{ $classroom->code }}</span>
                            
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Bootstrap 5 tabs -->
                    <ul class="nav nav-tabs mb-4" id="classroomTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="stream-tab" data-bs-toggle="tab" data-bs-target="#stream" type="button" role="tab" aria-controls="stream" aria-selected="true">Bảng tin</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="assignments-tab" data-bs-toggle="tab" data-bs-target="#assignments" type="button" role="tab" aria-controls="assignments" aria-selected="false">Bài tập</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="exams-tab" data-bs-toggle="tab" data-bs-target="#exams" type="button" role="tab" aria-controls="exams" aria-selected="false">Kiểm tra</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab" aria-controls="students" aria-selected="false">Học sinh</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="classroomTabContent">
                        <!-- Bảng tin -->
                        <div class="tab-pane fade show active" id="stream" role="tabpanel" aria-labelledby="stream-tab">
                            <div class="d-flex justify-content-center mb-4">
                                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                                    Tạo thông báo
                                </button>
                            </div>

                            <div class="timeline">
                                @forelse($announcements as $announcement)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $announcement->teacher->avatar }}" class="rounded-circle me-2" width="40">
                                            <div>
                                                <h5 class="mb-0">{{ $announcement->teacher->name }}</h5>
                                                <small>{{ $announcement->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        {!! $announcement->content !!}
                                    </div>
                                </div>
                                @empty
                                <div class="text-center">
                                    <p>Chưa có thông báo nào.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Bài tập -->
                        <div class="tab-pane fade" id="assignments" role="tabpanel" aria-labelledby="assignments-tab">
                            <div class="d-flex justify-content-center mb-4">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAssignmentModal">
                                    Tạo bài tập mới
                                </button>
                            </div>

                            <div class="assignments-list">
                                @forelse($assignments as $assignment)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-1">{{ $assignment->title }}</h5>
                                                <p class="text-muted mb-1">Hạn nộp: {{ $assignment->due_date->format('d/m/Y H:i') }}</p>
                                                <p class="mb-0">{{ Str::limit($assignment->description, 100) }}</p>
                                            </div>
                                            <div>
                                                <a href="{{ route('lecturer.assignment.detail', $assignment->id) }}" class="btn btn-sm btn-info">Chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center">
                                    <p>Chưa có bài tập nào.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Kiểm tra -->
                        <div class="tab-pane fade" id="exams" role="tabpanel" aria-labelledby="exams-tab">
                            <div class="d-flex justify-content-center mb-4">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createExamModal">
                                    Tạo bài kiểm tra mới
                                </button>
                            </div>

                            <div class="exams-list">
                                @forelse($exams as $exam)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-1">{{ $exam->title }}</h5>
                                                <p class="text-muted mb-1">
                                                    Thời gian: {{ $exam->start_time->format('d/m/Y H:i') }} - {{ $exam->end_time->format('d/m/Y H:i') }}
                                                </p>
                                                <p class="mb-0">{{ Str::limit($exam->description, 100) }}</p>
                                            </div>
                                            <div>
                                                <a href="{{ route('lecturer.exam.detail', $exam->id) }}" class="btn btn-sm btn-info">Chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center">
                                    <p>Chưa có bài kiểm tra nào.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Học sinh -->
                        <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
                            <div class="students-list">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Học sinh</th>
                                                <th>Email</th>
                                                <th>Ngày tham gia</th>
                                                <th>Tùy chọn</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($students as $student)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $student->avatar }}" class="rounded-circle me-2" width="40">
                                                        {{ $student->name }}
                                                    </div>
                                                </td>
                                                <td>{{ $student->email }}</td>
                                                <td>{{ $student->pivot->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="removeStudent('{{ $student->id }}')">
                                                        Xóa
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Chưa có học sinh nào tham gia lớp học.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal mã lớp học - Using Bootstrap 5 modal syntax -->
<div class="modal fade" id="classCodeModal" tabindex="-1" aria-labelledby="classCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="classCodeModalLabel">Mã lớp học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h2 class="display-4">{{ $classroom->code }}</h2>
                <p class="text-muted">Học sinh có thể sử dụng mã này để tham gia lớp học</p>
                <button class="btn btn-sm btn-secondary" onclick="copyToClipboard('{{ $classroom->code }}')">
                    Sao chép mã
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal tạo thông báo
<div class="modal fade" id="createAnnouncementModal" tabindex="-1" aria-labelledby="createAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAnnouncementModalLabel">Tạo thông báo mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('lecturer.classroom.show', $classroom->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="announcement_content" class="form-label">Nội dung thông báo</label>
                        <textarea class="form-control" id="announcement_content" name="content" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Đăng thông báo</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<!-- Modal tạo bài tập -->
<!-- <div class="modal fade" id="createAssignmentModal" tabindex="-1" aria-labelledby="createAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAssignmentModalLabel">Tạo bài tập mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('lecturer.classroom.show', $classroom->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assignment_title" class="form-label">Tiêu đề</label>
                        <input type="text" class="form-control" id="assignment_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="assignment_description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="assignment_description" name="description" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="assignment_due_date" class="form-label">Hạn nộp</label>
                        <input type="datetime-local" class="form-control" id="assignment_due_date" name="due_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="assignment_files" class="form-label">Tài liệu đính kèm (nếu có)</label>
                        <input type="file" class="form-control" id="assignment_files" name="files[]" multiple>
                    </div>
                    <div class="mb-3">
                        <label for="assignment_points" class="form-label">Điểm tối đa</label>
                        <input type="number" class="form-control" id="assignment_points" name="points" min="0" value="10">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tạo bài tập</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<!-- Modal tạo bài kiểm tra -->
<!-- <div class="modal fade" id="createExamModal" tabindex="-1" aria-labelledby="createExamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createExamModalLabel">Tạo bài kiểm tra mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('lecturer.classroom.show', $classroom->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exam_title" class="form-label">Tiêu đề</label>
                        <input type="text" class="form-control" id="exam_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="exam_description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="exam_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="exam_start_time" class="form-label">Thời gian bắt đầu</label>
                            <input type="datetime-local" class="form-control" id="exam_start_time" name="start_time" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="exam_end_time" class="form-label">Thời gian kết thúc</label>
                            <input type="datetime-local" class="form-control" id="exam_end_time" name="end_time" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exam_duration" class="form-label">Thời gian làm bài (phút)</label>
                        <input type="number" class="form-control" id="exam_duration" name="duration" min="5" value="45">
                    </div>
                    <div class="mb-3">
                        <label for="exam_points" class="form-label">Điểm tối đa</label>
                        <input type="number" class="form-control" id="exam_points" name="points" min="0" value="10">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tạo bài kiểm tra</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<script>
    function copyToClipboard(text) {
        // Modern clipboard API
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Đã sao chép mã lớp vào clipboard!');
            }, function() {
                // Fallback for older browsers
                fallbackCopyToClipboard(text);
            });
        } else {
            // Fallback for browsers without clipboard API
            fallbackCopyToClipboard(text);
        }
    }

    function fallbackCopyToClipboard(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('Đã sao chép mã lớp vào clipboard!');
    }
</script>
@endsection