<style>
    .create-question-modal {
        max-width: 800px;
    }

    .create-question-modal .modal-body {
        padding: 20px;
    }

    .option {
        margin-bottom: 10px;
    }

    #options-container input[type="text"] {
        flex-grow: 1;
        margin-right: 10px;
    }

    #temporaryQuestionsList {
        max-height: 300px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        background-color: #f8f9fa;
    }

    .modal-xl {
        max-width: 90%;
    }

    .modal-content {
        background: linear-gradient(145deg, #ffffff, #f6f9fc);
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .modal-body {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .modal-column {
        flex: 1;
        min-width: 300px;
    }

    .modal-section {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #e0e7ff;
        border-radius: 8px;
        background-color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease;
    }

    .modal-section:hover {
        transform: translateY(-2px);
    }

    .modal-section h6 {
        margin-bottom: 15px;
        color: #1e3a8a;
        font-weight: 600;
    }

    .form-label {
        font-weight: 500;
        color: #2d3748;
    }

    .form-select,
    .form-check-input {
        margin-top: 5px;
        border-color: #cbd5e1;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .question-list {
        max-height: 400px;
        overflow-y: auto;
        margin-top: 10px;
        padding: 15px;
        border: 1px solid #e0e7ff;
        border-radius: 8px;
        background-color: #f8fafc;
    }

    .selected-questions-list {
        max-height: 400px;
        overflow-y: auto;
        margin-top: 10px;
        padding: 15px;
        border: 1px solid #e0e7ff;
        border-radius: 8px;
        background-color: #f8fafc;
    }

    .topic-group {
        margin-bottom: 20px;
    }

    .topic-group h5 {
        background-color: #e0e7ff;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        font-size: 1.2rem;
        color: #1e40af;
    }

    .question-item {
        padding: 10px;
        border-bottom: 1px solid #e2e8f0;
        background-color: #ffffff;
        transition: background-color 0.2s;
    }

    .question-item:hover {
        background-color: #f1f5f9;
    }

    .question-item:last-child {
        border-bottom: none;
    }

    .question-item label {
        cursor: pointer;
        display: block;
    }

    .random-topics-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .random-topic-item {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #edf2ff;
        padding: 8px 12px;
        border-radius: 5px;
    }

    .topic-random-input {
        width: 60px;
        padding: 5px;
        border-color: #cbd5e1;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 15px;
        background-color: #f8fafc;
    }

    .form-check {
        margin-bottom: 10px;
    }

    .btn-primary {
        background-color: #2563eb;
        border-color: #2563eb;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-primary:disabled {
        background-color: #6b7280;
        border-color: #6b7280;
        cursor: not-allowed;
    }

    .btn-secondary {
        background-color: #6b7280;
        border-color: #6b7280;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
        border-color: #4b5563;
    }

    .alert {
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .modal-xl {
            max-width: 100%;
        }

        .modal-body {
            flex-direction: column;
            gap: 10px;
            padding: 15px;
        }

        .modal-column {
            width: 100%;
            min-width: unset;
        }

        .modal-section {
            margin-bottom: 15px;
            padding: 12px;
        }

        .question-list,
        .selected-questions-list {
            max-height: 300px;
        }

        .topic-random-input {
            width: 50px;
        }
    }

    .modal-header {
        border-bottom: 1px solid #e2e8f0;
        padding: 15px 20px;
        background-color: #f8fafc;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e3a8a;
    }

    .btn-close {
        padding: 10px;
        background-color: #e2e8f0;
        border-radius: 50%;
    }
</style>

<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @auth
        <!-- MODAL tạo mã đề -->
        <div class="modal fade" id="createSublistModal" tabindex="-1" aria-labelledby="createSublistModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form id="createSublistForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createSublistModalLabel">Tạo đề thi mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body d-flex flex-wrap gap-3 p-4">
                            <div class="modal-column" style="flex: 0 0 30%;">
                                <div class="modal-section mb-4 p-3 border rounded bg-white shadow-sm">
                                    <h6 class="mb-3">Thông tin đề thi</h6>
                                    <div class="mb-3">
                                        <label for="modal_sublist_title" class="form-label">Tiêu đề đề thi <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="modal_sublist_title"
                                            name="modal_sublist_title" required placeholder="Nhập tiêu đề đề thi">
                                        <small class="form-text text-muted">Tiêu đề giúp bạn nhận diện đề thi dễ
                                            dàng.</small>
                                    </div>
                                    <div class="mb-3 d-flex gap-2">
                                        <div style="flex: 1;">
                                            <label for="modal_course_id" class="form-label">Môn học <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="modal_course_id" name="modal_course_id"
                                                required>
                                                <option value="">Chọn môn học</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Chọn môn học để xem danh sách câu
                                                hỏi.</small>
                                        </div>
                                        <div style="flex: 1;">
                                            <label for="modal_list_question_id" class="form-label">Bộ câu hỏi</label>
                                            <select class="form-select" id="modal_list_question_id"
                                                name="modal_list_question_id">
                                                <option value="">Tất cả bộ câu hỏi</option>
                                            </select>
                                            <small class="form-text text-muted">Chọn bộ câu hỏi để lọc (tùy chọn).</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="modal_question_type" class="form-label">Loại câu hỏi <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="modal_question_type" name="modal_question_type"
                                            required>
                                            <option value="">Chọn loại câu hỏi</option>
                                            <option value="Trắc nghiệm">Trắc nghiệm</option>
                                            <option value="Tự luận">Tự luận</option>
                                        </select>
                                        <small class="form-text text-muted">Chọn loại câu hỏi cho đề thi.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="modal_topic" class="form-label">Chủ đề</label>
                                        <select class="form-select" id="modal_topic" name="modal_topic">
                                            <option value="">Tất cả chủ đề</option>
                                        </select>
                                        <small class="form-text text-muted">Chọn chủ đề để lọc câu hỏi (tùy chọn).</small>
                                    </div>
                                </div>
                                <div class="modal-section mb-4 p-3 border rounded bg-white shadow-sm">
                                    <h6 class="mb-3">Random câu hỏi theo chủ đề</h6>
                                    <div id="random_topics" class="random-topics-container"></div>
                                    <small class="form-text text-muted">Nhập số lượng câu hỏi muốn chọn ngẫu nhiên theo từng
                                        chủ đề.</small>
                                </div>
                                <div class="modal-section mb-4 p-3 border rounded bg-white shadow-sm">
                                    <h6 class="mb-3">Cài đặt</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="isShuffle" checked>
                                        <label class="form-check-label" for="isShuffle">Trộn thứ tự câu hỏi</label>
                                        <small class="form-text text-muted">Bật để sắp xếp ngẫu nhiên thứ tự câu hỏi trong
                                            đề thi.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-column" style="flex: 0 0 35%;">
                                <div class="modal-section mb-4 p-3 border rounded bg-white shadow-sm">
                                    <h6 class="mb-3">Danh sách câu hỏi</h6>
                                    <div id="question_list" class="question-list">
                                        <p class="text-muted">Vui lòng chọn môn học và loại câu hỏi để hiển thị danh sách
                                            câu hỏi.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-column" style="flex: 0 0 30%;">
                                <div class="modal-section mb-4 p-3 border rounded bg-white shadow-sm">
                                    <h6 class="mb-3">Câu hỏi đã chọn</h6>
                                    <div id="selected_questions_list" class="selected-questions-list">
                                        <p class="text-muted">Chưa có câu hỏi nào được chọn.</p>
                                    </div>
                                    <div id="question_type_error" class="alert alert-danger d-none" role="alert">
                                        Lỗi: Không được chọn cả câu hỏi Trắc nghiệm và Tự luận trong cùng một đề thi!
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary" id="create_sublist_submit" disabled>Tạo mã
                                đề</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- MODAL tạo bộ câu hỏi -->
        <div class="modal fade" id="createListQuestionModal" tabindex="-1" aria-labelledby="createListQuestionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog create-question-modal">
                <div class="modal-content">
                    <form id="createListQuestionForm" novalidate>
                        <div class="modal-header">
                            <h5 class="modal-title" id="createListQuestionModalLabel">Tạo bộ câu hỏi mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Môn học</label>
                                <select class="form-select" id="course_id" name="course_id" required>
                                    <option value="">Chọn môn học</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3" id="topic_container" style="display: none;">
                                <label for="topic" class="form-label">Chủ đề</label>
                                <select class="form-select" id="topic_select" name="topic_select">
                                    <option value="">Chọn chủ đề</option>
                                    <option value="new">Nhập chủ đề mới</option>
                                </select>
                                <input type="text" class="form-control mt-2" id="topic_input" name="topic"
                                    style="display: none;" placeholder="Nhập chủ đề mới">
                            </div>
                            <div id="temporaryQuestionsSection" class="mb-4" style="display: none;">
                                <h5>Câu hỏi đã lưu tạm thời:</h5>
                                <div id="temporaryQuestionsList"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" id="startCreateQuestion" class="btn btn-primary">Tạo bộ câu hỏi</button>
                        </div>
                    </form>
                    <div class="card shadow-lg" id="questionFormCard" style="display: none;">
                        <div class="card-header bg-primary text-white text-center">
                            <h4 class="mb-0">Tạo Câu Hỏi Trắc Nghiệm</h4>
                        </div>
                        <div class="card-body p-4">
                            <form id="createQuestionForm">
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-bold">Tiêu Đề Câu Hỏi</label>
                                    <input type="text" id="title" name="title" class="form-control"
                                        placeholder="Nhập tiêu đề câu hỏi" required>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label fw-bold">Nội Dung Câu Hỏi</label>
                                    <textarea id="content" name="content" class="form-control" rows="4"
                                        placeholder="Nhập nội dung câu hỏi" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="type" class="form-label fw-bold">Loại Câu Hỏi</label>
                                    <select id="type" name="type" class="form-select" required>
                                        <option value="Trắc nghiệm">Trắc nghiệm</option>
                                        <option value="Tự luận">Tự luận</option>
                                    </select>
                                </div>
                                <div id="options-section" class="mb-3">
                                    <label class="form-label fw-bold">Đáp Án</label>
                                    <div id="options-container">
                                        <div class="d-flex align-items-center mb-2 option">
                                            <input type="text" name="options[0][option_text]" class="form-control me-2"
                                                placeholder="Nhập đáp án 1" required>
                                            <input type="radio" name="correct_answer" value="0" class="form-check-input">
                                            <span class="ms-2">Đáp án đúng</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-secondary mt-2" onclick="addOption()">+
                                        Thêm Lựa Chọn</button>
                                </div>
                                <div class="text-center">
                                    <button type="button" id="saveQuestion" class="btn btn-primary w-100 mt-3" disabled>Tạo
                                        Thêm Câu Hỏi</button>
                                    <button type="button" id="finishCreating" class="btn btn-success w-100 mt-3"
                                        disabled>Hoàn Thành</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="createQuestionBankModal" tabindex="-1" aria-labelledby="createQuestionBankModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createQuestionBankModalLabel">Tạo đề thi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createQuestionBankForm">
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Khóa học</label>
                                <select class="form-select" id="course_id" name="course_id" required>
                                    <option value="">Chọn khóa học</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exam_name" class="form-label">Tên đề thi</label>
                                <input type="text" class="form-control" id="exam_name" name="exam_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="topic" class="form-label">Chủ đề</label>
                                <select class="form-select" id="topic" name="topic">
                                    <option value="">Tất cả</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="question_type" class="form-label">Loại câu hỏi</label>
                                <select class="form-select" id="question_type" name="question_type">
                                    <option value="Trắc nghiệm">Trắc nghiệm</option>
                                    <option value="Tự luận">Tự luận</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Danh sách câu hỏi</label>
                                <div id="question_list" class="border p-3" style="max-height: 300px; overflow-y: auto;">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Câu hỏi đã chọn</label>
                                <div id="selected_questions_list" class="border p-3"
                                    style="max-height: 200px; overflow-y: auto;"></div>
                            </div>
                            <div id="error-message" class="text-danger"></div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="saveQuestionBank">Lưu</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-2">
            <button type="button" class="btn btn-primary mb-4" onclick="createSublist()">Tạo đề thi</button>
            <button type="button" class="btn btn-primary mb-4" onclick="showCreateListQuestionModal()">Tạo bộ câu hỏi
                mới</button>
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="list-question-tab" data-bs-toggle="tab" data-bs-target="#list-question"
                    type="button" role="tab" aria-controls="list-question" aria-selected="true">Danh sách bộ câu
                    hỏi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sublist-tab" data-bs-toggle="tab" data-bs-target="#sublist" type="button"
                    role="tab" aria-controls="sublist" aria-selected="false">Danh sách mã đề</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="list-question" role="tabpanel" aria-labelledby="list-question-tab">
                <div class="flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-4">Danh sách bộ câu hỏi</h3>
                    <div class="d-flex gap-3">
                        <select name="course_id" id="courseFilter"
                            class="form-select bg-body-secondary text-black p-3 border-dark">
                            <option value="all" selected>Tất cả</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                            @endforeach
                        </select>
                        <select name="topic_filter" id="topicFilter"
                            class="form-select bg-body-secondary text-black p-3 border-dark">
                            <option value="all" selected>Tất cả chủ đề</option>
                        </select>
                    </div>
                </div>
                <div id="list-question-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 cursor-pointer">
                </div>
            </div>
            <div class="tab-pane fade" id="sublist" role="tabpanel" aria-labelledby="sublist-tab">
                <div class="flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-4">Danh sách mã đề</h3>
                    <select name="course_id" id="sublistCourseFilter"
                        class="form-select bg-body-secondary text-black p-3 border-dark">
                        <option value="all" selected>Tất cả</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="sublist-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"></div>
            </div>
        </div>
    @endauth
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="lecturer-id" content="{{ Auth::user()->lecturer_id }}">

<script>
    function createSublist() {
        const modal = new bootstrap.Modal(document.getElementById('createSublistModal'));
        modal.show();
    }
    function showCreateListQuestionModal() {
        const modal = new bootstrap.Modal(document.getElementById('createListQuestionModal'));
        modal.show();
    }
    document.addEventListener("DOMContentLoaded", function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const lecturerId = document.querySelector('meta[name="lecturer-id"]').getAttribute('content');
        let optionCount = 1;
        let isSubmitting = false;

        const createListQuestionModal = document.getElementById('createListQuestionModal');
        createListQuestionModal.addEventListener('hidden.bs.modal', function () {
            localStorage.removeItem('list_question_id');
            localStorage.removeItem('questions');
            document.getElementById('questionFormCard').style.display = 'none';
            document.getElementById('temporaryQuestionsSection').style.display = 'none';
            document.getElementById('saveQuestion').disabled = true;
            document.getElementById('finishCreating').disabled = true;
            isSubmitting = false;
        });

        function attachFormSubmitHandler() {
            const form = document.getElementById('createListQuestionForm');
            if (form) {
                form.removeEventListener('submit', formSubmitHandler);
                form.addEventListener('submit', formSubmitHandler);
            } else {
                console.error('Cannot find form #createListQuestionForm when attaching submit handler');
            }
        }

        function formSubmitHandler(e) {
            e.preventDefault();
            if (isSubmitting) {
                return;
            }
            isSubmitting = true;

            const existingListQuestionId = localStorage.getItem('list_question_id');
            if (existingListQuestionId) {
                const questionFormCard = document.getElementById('questionFormCard');
                const temporaryQuestionsSection = document.getElementById('temporaryQuestionsSection');
                if (questionFormCard && temporaryQuestionsSection) {
                    questionFormCard.style.display = 'block';
                    temporaryQuestionsSection.style.display = 'block';
                    document.getElementById('saveQuestion').disabled = false;
                    document.getElementById('finishCreating').disabled = false;
                    renderTemporaryQuestions();
                }
                isSubmitting = false;
                return;
            }

            const courseId = document.getElementById('course_id').value;
            const topicSelect = document.getElementById('topic_select');
            const topicInput = document.getElementById('topic_input');
            let topic = topicSelect.value === 'new' ? topicInput.value.trim() : topicSelect.value;

            if (!courseId || !topic) {
                alert('Vui lòng chọn môn học và chủ đề!');
                isSubmitting = false;
                return;
            }

            fetch('/api/list-questions/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    course_id: courseId,
                    topic: topic,
                    lecturer_id: lecturerId
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        localStorage.setItem('list_question_id', data.list_question_id);
                        const questionFormCard = document.getElementById('questionFormCard');
                        const temporaryQuestionsSection = document.getElementById('temporaryQuestionsSection');
                        if (questionFormCard && temporaryQuestionsSection) {
                            questionFormCard.style.display = 'block';
                            temporaryQuestionsSection.style.display = 'block';
                            document.getElementById('saveQuestion').disabled = false;
                            document.getElementById('finishCreating').disabled = false;
                            renderTemporaryQuestions();
                        } else {
                            console.error('Missing DOM elements:', {
                                questionFormCard: !!questionFormCard,
                                temporaryQuestionsSection: !!temporaryQuestionsSection
                            });
                            alert('Lỗi giao diện: Không thể hiển thị form tạo câu hỏi!');
                        }
                    } else {
                        alert(`Lỗi từ server: ${data.message || 'Không thể tạo bộ câu hỏi!'}`);
                    }
                })
                .catch(error => {
                    console.error('API error:', error);
                    alert(`Lỗi kết nối: ${error.message}`);
                })
                .finally(() => {
                    isSubmitting = false;
                    const startCreateQuestionBtn = document.getElementById('startCreateQuestion');
                    if (startCreateQuestionBtn) {
                        startCreateQuestionBtn.disabled = false;
                    }
                });
        }

        attachFormSubmitHandler();

        const startCreateQuestionBtn = document.getElementById('startCreateQuestion');
        if (startCreateQuestionBtn) {
            startCreateQuestionBtn.addEventListener('click', function (e) {
                const form = document.getElementById('createListQuestionForm');
                if (form) {
                    this.disabled = true;
                    form.dispatchEvent(new Event('submit'));
                } else {
                    console.error('Form #createListQuestionForm not found when button clicked');
                }
            });
        } else {
            console.error('Cannot find button #startCreateQuestion');
        }

        document.getElementById('createQuestionForm')?.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        const courseToCreateQuestion = document.getElementById("course_id");
        if (courseToCreateQuestion) {
            courseToCreateQuestion.addEventListener('change', function () {
                const courseId = this.value;
                const topicContainer = document.getElementById('topic_container');
                const topicSelect = document.getElementById('topic_select');
                const topicInput = document.getElementById('topic_input');
                const startCreateQuestion = document.getElementById('startCreateQuestion');

                if (courseId) {
                    topicContainer.style.display = 'block';
                    fetch(`/api/list-questions/topics/${courseId}`)
                        .then(response => response.json())
                        .then(data => {
                            topicSelect.innerHTML = '<option value="">Chọn chủ đề</option>';
                            if (data.topics && data.topics.length > 0) {
                                data.topics.forEach(topic => {
                                    const option = document.createElement('option');
                                    option.value = topic;
                                    option.textContent = topic;
                                    topicSelect.appendChild(option);
                                });
                            }
                            const newOption = document.createElement('option');
                            newOption.value = 'new';
                            newOption.textContent = 'Nhập chủ đề mới';
                            topicSelect.appendChild(newOption);
                            startCreateQuestion.disabled = false;
                        })
                        .catch(error => console.error('Lỗi khi lấy danh sách topic:', error));
                } else {
                    topicContainer.style.display = 'none';
                    startCreateQuestion.disabled = true;
                }
            });
        }
        document.getElementById('topic_select')?.addEventListener('change', function () {
            const topicInput = document.getElementById('topic_input');
            if (this.value === 'new') {
                topicInput.style.display = 'block';
                topicInput.focus();
            } else {
                topicInput.style.display = 'none';
                topicInput.value = '';
            }
        });
        window.addOption = function () {
            const container = document.getElementById('options-container');
            const optionDiv = document.createElement('div');
            optionDiv.classList.add('d-flex', 'align-items-center', 'mb-2', 'option');
            optionDiv.innerHTML = `
            <input type="text" name="options[${optionCount}][option_text]" class="form-control me-2" placeholder="Nhập đáp án ${optionCount + 1}" required>
            <input type="radio" name="correct_answer" value="${optionCount}" class="form-check-input">
            <span class="ms-2">Đáp án đúng</span>
        `;
            container.appendChild(optionDiv);
            optionCount++;
        };
        document.getElementById('type')?.addEventListener('change', function () {
            document.getElementById('options-section').style.display = this.value === 'Trắc nghiệm' ? 'block' : 'none';
        });
        document.getElementById('saveQuestion')?.addEventListener('click', function () {
            const listQuestionId = localStorage.getItem("list_question_id");
            if (!listQuestionId) return alert("Vui lòng tạo bộ câu hỏi trước!");
            const title = document.getElementById('title').value;
            const content = document.getElementById('content').value;
            const type = document.getElementById('type').value;
            if (!title || !content) return alert("Vui lòng nhập đầy đủ thông tin!");
            const options = [];
            let hasCorrectAnswer = false;
            if (type === 'Trắc nghiệm') {
                document.querySelectorAll('.option').forEach((optionElement, index) => {
                    const optionText = optionElement.querySelector('input[type="text"]').value;
                    const isCorrect = optionElement.querySelector('input[type="radio"]').checked;
                    if (optionText) {
                        options.push({ option_text: optionText, is_correct: isCorrect });
                        if (isCorrect) hasCorrectAnswer = true;
                    }
                });
                if (options.length < 2 || !hasCorrectAnswer) return alert("Trắc nghiệm cần ít nhất 2 đáp án và 1 đáp án đúng!");
            }
            const question = { title, content, type, options };
            let savedQuestions = JSON.parse(localStorage.getItem('questions')) || [];
            savedQuestions.push(question);
            localStorage.setItem('questions', JSON.stringify(savedQuestions));
            resetForm();
            renderTemporaryQuestions();
        });
        document.getElementById('finishCreating')?.addEventListener('click', function () {
            const savedQuestions = JSON.parse(localStorage.getItem('questions')) || [];
            const listQuestionId = localStorage.getItem("list_question_id");
            if (savedQuestions.length === 0 || !listQuestionId) return alert("Chưa có câu hỏi nào hoặc bộ câu hỏi không tồn tại!");
            fetch('/api/questions/batch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    list_question_id: listQuestionId,
                    questions: savedQuestions
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        localStorage.removeItem('questions');
                        localStorage.removeItem('list_question_id');
                        alert("Hoàn thành tạo bộ câu hỏi!");
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createListQuestionModal'));
                        modal.hide();
                        fetchListQuestions();
                    }
                })
                .catch(error => console.error('Lỗi:', error));
        });
        function resetForm() {
            const form = document.getElementById('createQuestionForm');
            if (form) {
                form.reset();
            } else {
                console.error('Cannot find form #createQuestionForm for reset');
                return;
            }
            const optionsContainer = document.getElementById('options-container');
            if (optionsContainer) {
                optionsContainer.innerHTML = `
                <div class="d-flex align-items-center mb-2 option">
                    <input type="text" name="options[0][option_text]" class="form-control me-2" placeholder="Nhập đáp án 1" required>
                    <input type="radio" name="correct_answer" value="0" class="form-check-input">
                    <span class="ms-2">Đáp án đúng</span>
                </div>
            `;
            } else {
                console.error('Cannot find options-container for reset');
            }
            optionCount = 1;
        }

        function renderTemporaryQuestions() {
            const savedQuestions = JSON.parse(localStorage.getItem('questions')) || [];
            const temporaryQuestionsList = document.getElementById("temporaryQuestionsList");

            if (!Array.isArray(savedQuestions) || savedQuestions.length === 0) {
                temporaryQuestionsList.innerHTML = "<p>Không có câu hỏi tạm thời nào.</p>";
                return;
            }

            let html = '';

            savedQuestions.forEach((q) => {
                html += `
                <div class="question-item">
                    <strong>${q.title} (${q.type})</strong>
                    <p>${q.content}</p>
            `;

                if (Array.isArray(q.options)) {
                    html += '<ul>';
                    q.options.forEach(option => {
                        html += `<li>${option.option_text} ${option.is_correct == 1 ? '(Đúng)' : ''}</li>`;
                    });
                    html += '</ul>';
                }

                html += `
                <button class="btn btn-danger btn-sm delete-question" data-id="${q.question_id}">Xóa</button>
                <hr>
                </div>
            `;
            });

            temporaryQuestionsList.innerHTML = html;

            document.querySelectorAll('.delete-question').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    let questions = JSON.parse(localStorage.getItem('questions')) || [];
                    questions = questions.filter(q => q.question_id !== id);
                    localStorage.setItem('questions', JSON.stringify(questions));
                    renderTemporaryQuestions(); // Re-render after delete
                });
            });
        }

        document.addEventListener('DOMContentLoaded', renderTemporaryQuestions);

        document.getElementById('courseFilter').addEventListener('change', function () {
            const courseId = this.value;
            const topicFilter = document.getElementById('topicFilter');
            if (!topicFilter) {
                console.error('Không tìm thấy phần tử topicFilter trong DOM');
                return;
            }
            fetchListQuestions(courseId, 'all');
            if (courseId !== 'all') {
                fetch(`/api/list-questions/topics/${courseId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Lỗi khi lấy danh sách topic');
                        return response.json();
                    })
                    .then(data => {
                        topicFilter.innerHTML = '<option value="all" selected>Tất cả chủ đề</option>';
                        if (data.topics && data.topics.length > 0) {
                            data.topics.forEach(topic => {
                                topicFilter.innerHTML += `<option value="${topic}">${topic}</option>`;
                            });
                        }
                    })
                    .catch(error => console.error('Lỗi khi lấy topic:', error));
            } else {
                topicFilter.innerHTML = '<option value="all" selected>Tất cả chủ đề</option>';
            }
        });
        document.getElementById('topicFilter')?.addEventListener('change', function () {
            const courseId = document.getElementById('courseFilter').value;
            const topic = this.value;
            fetchListQuestions(courseId, topic);
        });
        document.getElementById('modal_course_id').addEventListener('change', function () {
            const courseId = this.value;
            const listQuestionSelect = document.getElementById('modal_list_question_id');
            const topicSelect = document.getElementById('modal_topic');
            const randomTopics = document.getElementById('random_topics');
            const questionType = document.getElementById('modal_question_type').value;

            listQuestionSelect.innerHTML = '<option value="">Tất cả bộ câu hỏi</option>';
            topicSelect.innerHTML = '<option value="">Tất cả chủ đề</option>';
            randomTopics.innerHTML = '';

            if (courseId) {
                fetch(`/api/list-questions/${lecturerId}?course_id=${courseId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.list_question_id;
                            option.textContent = item.topic || 'Bộ câu hỏi không có chủ đề';
                            listQuestionSelect.appendChild(option);
                        });

                        if (questionType) {
                            updateQuestionList(courseId, questionType, '', '');
                            const topics = [...new Set(data.map(item => item.topic))];
                            topics.forEach(topic => {
                                const option = document.createElement('option');
                                option.value = topic;
                                option.textContent = topic;
                                topicSelect.appendChild(option);
                            });

                            topics.forEach(topic => {
                                const topicQuestions = data.filter(item => item.topic === topic);
                                let questionCount = 0;
                                topicQuestions.forEach(item => {
                                    fetch(`/api/list-questions/detail/${item.list_question_id}`)
                                        .then(res => res.json())
                                        .then(detail => {
                                            const questions = detail.data.questions.filter(q => q.type === questionType);
                                            questionCount += questions.length;
                                            const div = document.createElement('div');
                                            div.className = 'random-topic-item';
                                            div.innerHTML = `
                                            <label>${topic} (${questionCount} câu)</label>
                                            <input type="number" class="topic-random-input form-control form-control-sm" data-topic="${topic}" min="0" max="${questionCount}" placeholder="Số câu">
                                        `;
                                            randomTopics.appendChild(div);
                                        })
                                        .catch(error => console.error(`Lỗi khi lấy chi tiết list_question_id ${item.list_question_id}:`, error));
                                });
                            });
                        }
                    })
                    .catch(error => console.error('Lỗi khi lấy danh sách list-questions:', error));
            } else {
                document.getElementById('question_list').innerHTML = '<p class="text-muted">Vui lòng chọn môn học và loại câu hỏi.</p>';
            }
        });
        document.getElementById('modal_list_question_id').addEventListener('change', function () {
            const courseId = document.getElementById('modal_course_id').value;
            const listQuestionId = this.value;
            const questionType = document.getElementById('modal_question_type').value;
            const topic = document.getElementById('modal_topic').value;
            if (courseId && questionType) {
                updateQuestionList(courseId, questionType, topic, listQuestionId);
            }
        });
        document.getElementById('modal_question_type').addEventListener('change', function () {
            const courseId = document.getElementById('modal_course_id').value;
            const listQuestionId = document.getElementById('modal_list_question_id').value;
            const questionType = this.value;
            const topic = document.getElementById('modal_topic').value;
            if (courseId && questionType) {
                updateQuestionList(courseId, questionType, topic, listQuestionId);
                document.getElementById('modal_course_id').dispatchEvent(new Event('change'));
            } else {
                document.getElementById('question_list').innerHTML = '<p class="text-muted">Vui lòng chọn môn học và loại câu hỏi.</p>';
            }
        });
        document.getElementById('modal_topic').addEventListener('change', function () {
            const courseId = document.getElementById('modal_course_id').value;
            const listQuestionId = document.getElementById('modal_list_question_id').value;
            const questionType = document.getElementById('modal_question_type').value;
            const topic = this.value;
            if (courseId && questionType) {
                updateQuestionList(courseId, questionType, topic, listQuestionId);
            }
        });
        function updateQuestionList(courseId, questionType, topic = '', listQuestionId = '') {
            const questionList = document.getElementById('question_list');
            questionList.innerHTML = '<p class="text-muted">Đang tải câu hỏi...</p>';
            fetch(`/api/list-questions/${lecturerId}?course_id=${courseId}`)
                .then(response => response.json())
                .then(data => {
                    let filteredData = data;
                    if (listQuestionId) {
                        filteredData = data.filter(item => item.list_question_id === listQuestionId);
                    }
                    const promises = filteredData.map(item =>
                        fetch(`/api/list-questions/detail/${item.list_question_id}`)
                            .then(res => res.json())
                            .then(detail => {
                                return {
                                    topic: item.topic,
                                    list_question_id: item.list_question_id,
                                    questions: detail.data.questions
                                        .filter(q => q.type === questionType)
                                        .map(q => ({
                                            ...q,
                                            list_question_id: item.list_question_id
                                        }))
                                };
                            })
                            .catch(error => {
                                console.error(`Lỗi khi lấy chi tiết list_question_id ${item.list_question_id}:`, error);
                                return { topic: item.topic, list_question_id: item.list_question_id, questions: [] };
                            })
                    );
                    Promise.all(promises).then(results => {
                        questionList.innerHTML = '';
                        const filteredResults = topic ? results.filter(r => r.topic === topic) : results;
                        if (filteredResults.length === 0 || filteredResults.every(r => r.questions.length === 0)) {
                            questionList.innerHTML = '<p class="text-muted">Không có câu hỏi nào phù hợp.</p>';
                            return;
                        }
                        filteredResults.forEach(result => {
                            if (result.questions.length > 0) {
                                const topicDiv = document.createElement('div');
                                topicDiv.className = 'topic-group';
                                topicDiv.innerHTML = `<h5>Chủ đề: ${result.topic}</h5>`;
                                result.questions.forEach(q => {
                                    const questionDiv = document.createElement('div');
                                    questionDiv.className = 'question-item';
                                    // Xử lý options là mảng đối tượng
                                    const optionsHtml = q.type === 'Trắc nghiệm' && Array.isArray(q.options) && q.options.length > 0
                                        ? `<ul>${q.options.map(o => `<li>${o.option_text || 'Không có nội dung'} ${o.is_correct ? '(Đúng)' : ''}</li>`).join('')}</ul>`
                                        : q.type === 'Trắc nghiệm' ? '<p class="text-muted">Không có đáp án</p>' : '';
                                    questionDiv.innerHTML = `
                                <div class="form-check">
                                    <input class="form-check-input question-checkbox" type="checkbox" data-id="${q.question_id}" data-list-id="${result.list_question_id}" data-type="${q.type}" id="question-${q.question_id}">
                                    <label class="form-check-label" for="question-${q.question_id}">
                                        <strong>${q.title || 'Không có tiêu đề'}</strong> (${q.type})<br>
                                        ${q.content || 'Không có nội dung'}<br>
                                        ${optionsHtml}
                                    </label>
                                </div>
                            `;
                                    topicDiv.appendChild(questionDiv);
                                });
                                questionList.appendChild(topicDiv);
                            }
                        });

                        // Gắn lại sự kiện cho checkbox
                        document.querySelectorAll('.question-checkbox').forEach(checkbox => {
                            checkbox.removeEventListener('change', handleCheckboxChange);
                            checkbox.addEventListener('change', handleCheckboxChange);
                        });

                        function handleCheckboxChange() {
                            let selectedQuestions = JSON.parse(localStorage.getItem('selectedQuestions')) || [];
                            const questionId = this.getAttribute('data-id');
                            const listQuestionId = this.getAttribute('data-list-id');
                            const questionType = this.getAttribute('data-type');
                            if (!questionId || !listQuestionId || listQuestionId === 'undefined') {
                                console.error('Invalid question data:', { questionId, listQuestionId });
                                return;
                            }
                            if (this.checked) {
                                if (!selectedQuestions.some(q => q.question_id === questionId)) {
                                    selectedQuestions.push({ question_id: questionId, list_question_id: listQuestionId, type: questionType });
                                }
                            } else {
                                selectedQuestions = selectedQuestions.filter(item => item.question_id !== questionId);
                            }
                            localStorage.setItem('selectedQuestions', JSON.stringify(selectedQuestions));
                            validateQuestionTypes(selectedQuestions);
                            renderSelectedQuestions();
                        }
                    });
                })
                .catch(error => {
                    console.error('Lỗi khi lấy danh sách list-questions:', error);
                    questionList.innerHTML = '<p class="text-danger">Lỗi khi tải câu hỏi.</p>';
                });
        }

        function validateQuestionTypes(selectedQuestions) {
            const submitButton = document.getElementById('create_sublist_submit');
            const errorDiv = document.getElementById('question_type_error');
            if (selectedQuestions.length === 0) {
                submitButton.disabled = true;
                errorDiv.classList.add('d-none');
                return;
            }
            const types = [...new Set(selectedQuestions.map(q => q.type))];
            if (types.length > 1) {
                errorDiv.classList.remove('d-none');
                submitButton.disabled = true;
            } else {
                errorDiv.classList.add('d-none');
                submitButton.disabled = false;
            }
        }

        function renderSelectedQuestions() {
            const selectedQuestions = JSON.parse(localStorage.getItem('selectedQuestions')) || [];
            const selectedQuestionsList = document.getElementById('selected_questions_list');
            if (selectedQuestions.length === 0) {
                selectedQuestionsList.innerHTML = '<p class="text-muted">Chưa có câu hỏi nào được chọn.</p>';
                return;
            }
            const promises = selectedQuestions.map(q =>
                fetch(`/api/questions/getById/${q.question_id}`)
                    .then(res => res.json())
                    .then(data => ({
                        ...data,
                        list_question_id: q.list_question_id
                    }))
                    .catch(error => {
                        console.error(`Lỗi khi lấy chi tiết câu hỏi ${q.question_id}:`, error);
                        return null;
                    })
            );
            Promise.all(promises).then(questions => {
                selectedQuestionsList.innerHTML = questions
                    .filter(q => q !== null)
                    .map(q => {
                        // Xử lý options là mảng đối tượng
                        const optionsHtml = q.type === 'Trắc nghiệm' && Array.isArray(q.options) && q.options.length > 0
                            ? `<ul>${q.options.map(o => `<li>${o.option_text || 'Không có nội dung'} ${o.is_correct ? '(Đúng)' : ''}</li>`).join('')}</ul>`
                            : q.type === 'Trắc nghiệm' ? '<p class="text-muted">Không có đáp án</p>' : '';
                        return `
                    <div class="question-item">
                        <strong>${q.title || 'Không có tiêu đề'}</strong> (${q.type})<br>
                        ${q.content || 'Không có nội dung'}<br>
                        ${optionsHtml}
                    </div>
                `;
                    })
                    .join('');
            });
        }

        document.getElementById('createSublistModal').addEventListener('show.bs.modal', function () {
            localStorage.removeItem('selectedQuestions');
            document.getElementById('selected_questions_list').innerHTML = '<p class="text-muted">Chưa có câu hỏi nào được chọn.</p>';
            document.getElementById('question_type_error').classList.add('d-none');
            document.getElementById('create_sublist_submit').disabled = true;
            console.log('Cleared selectedQuestions on modal open');
        });

        document.getElementById('createSublistForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const title = document.getElementById('modal_sublist_title').value;
            const questionType = document.getElementById('modal_question_type').value;
            const isShuffle = document.getElementById('isShuffle').checked;
            let selectedQuestions = JSON.parse(localStorage.getItem('selectedQuestions')) || [];
            const randomInputs = document.querySelectorAll('.topic-random-input');
            const courseId = document.getElementById('modal_course_id').value;
            const listQuestionId = document.getElementById('modal_list_question_id').value;

            // Xử lý random câu hỏi
            for (const input of randomInputs) {
                const topic = input.getAttribute('data-topic');
                const count = parseInt(input.value) || 0;
                if (count > 0) {
                    try {
                        const response = await fetch(`/api/list-questions/${lecturerId}?course_id=${courseId}`);
                        const data = await response.json();
                        let topicItems = data.filter(item => item.topic === topic);
                        if (listQuestionId) {
                            topicItems = topicItems.filter(item => item.list_question_id === listQuestionId);
                        }
                        for (const item of topicItems) {
                            const detailResponse = await fetch(`/api/list-questions/detail/${item.list_question_id}`);
                            const detail = await detailResponse.json();
                            const filteredQuestions = detail.data.questions.filter(q => q.type === questionType);
                            if (filteredQuestions.length < count) {
                                alert(`Không đủ câu hỏi cho chủ đề ${topic}. Yêu cầu ${count} câu, nhưng chỉ có ${filteredQuestions.length} câu.`);
                                return;
                            }
                            const shuffled = filteredQuestions.sort(() => 0.5 - Math.random());
                            const selected = shuffled.slice(0, count).map(q => ({
                                question_id: q.question_id,
                                list_question_id: item.list_question_id,
                                type: q.type
                            }));
                            selected.forEach(q => {
                                if (!selectedQuestions.some(existing => existing.question_id === q.question_id)) {
                                    selectedQuestions.push(q);
                                }
                            });
                        }
                    } catch (error) {
                        console.error(`Lỗi khi lấy câu hỏi ngẫu nhiên cho chủ đề ${topic}:`, error);
                        alert(`Lỗi khi chọn câu hỏi ngẫu nhiên cho chủ đề ${topic}.`);
                        return;
                    }
                }
            }

            // Lọc trùng lặp và loại bỏ các câu hỏi có list_question_id không hợp lệ
            selectedQuestions = selectedQuestions.filter(
                (q, index, self) =>
                    q.question_id &&
                    q.list_question_id &&
                    q.list_question_id !== 'undefined' &&
                    self.findIndex(item => item.question_id === q.question_id) === index
            );
            localStorage.setItem('selectedQuestions', JSON.stringify(selectedQuestions));
            console.log('Final selectedQuestions:', selectedQuestions);

            // Kiểm tra loại câu hỏi trước khi gửi
            validateQuestionTypes(selectedQuestions);
            if (selectedQuestions.length === 0) {
                alert('Vui lòng chọn ít nhất một câu hỏi!');
                return;
            }
            if (!title) {
                alert('Vui lòng nhập tiêu đề đề thi!');
                return;
            }
            if (!questionType) {
                alert('Vui lòng chọn loại câu hỏi!');
                return;
            }
            const types = [...new Set(selectedQuestions.map(q => q.type))];
            if (types.length > 1) {
                alert('Không được chọn cả câu hỏi Trắc nghiệm và Tự luận trong cùng một đề thi!');
                return;
            }

            try {
                const res = await fetch('http://localhost:8000/api/sub-lists/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        title: title,
                        isShuffle,
                        question_ids: selectedQuestions.map(q => q.question_id)
                    })
                });
                const data = await res.json();
                if (res.ok) {
                    alert("✅ Tạo mã đề thành công!");
                    localStorage.removeItem('selectedQuestions');
                    document.getElementById('createSublistForm').reset();
                    document.getElementById('selected_questions_list').innerHTML = '<p class="text-muted">Chưa có câu hỏi nào được chọn.</p>';
                    document.getElementById('create_sublist_submit').disabled = true;
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createSublistModal'));
                    modal.hide();
                    fetchSubLists();
                } else {
                    alert(`❌ Lỗi tạo mã đề: ${data.message || 'Có lỗi xảy ra.'}`);
                }
            } catch (err) {
                console.error('Lỗi khi gửi yêu cầu tạo mã đề:', err);
                alert("❌ Lỗi kết nối đến server.");
            }
        });
        function fetchListQuestions(courseId = "all", topic = "all") {
            let url = `/api/list-questions/${lecturerId}`;
            const params = [];
            if (courseId !== "all") params.push(`course_id=${courseId}`);
            if (topic !== "all") params.push(`topic=${encodeURIComponent(topic)}`);
            if (params.length > 0) url += `?${params.join('&')}`;
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Lỗi khi lấy danh sách bộ câu hỏi');
                    return response.json();
                })
                .then(data => {
                    const container = document.getElementById("list-question-container");
                    container.innerHTML = data.length > 0 ?
                        data.map(item => `
                    <div class="col">
                        <div class="card h-100 shadow-sm card-hover" data-id="${item.list_question_id}">
                            <div class="card-body">
                                <p><strong>Môn học:</strong> ${item.course?.course_name || "Không rõ"}</p>
                                <p><strong>Chủ đề:</strong> ${item.topic}</p>
                                <p><small>Tạo lúc: ${new Date(item.created_at).toLocaleString()}</small></p>
                                <a href="/lecturer/chi_tiet_bo_cau_hoi/${item.list_question_id}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                `).join('') : `<p class="text-muted">Chưa có bộ câu hỏi nào.</p>`;
                })
                .catch(error => {
                    console.error('Lỗi khi lấy bộ câu hỏi:', error);
                    document.getElementById("list-question-container").innerHTML = `<p class="text-danger">Lỗi khi tải dữ liệu.</p>`;
                });
        }
        function fetchSubLists(courseId = "all") {
            const container = document.getElementById("sublist-container");
            container.innerHTML = "Đang tải...";
            let url = `/api/sub-lists/by-lecturer/${lecturerId}`;
            if (courseId !== "all") {
                url += `?course_id=${courseId}`;
            }
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    container.innerHTML = "";
                    if (!data.sub_list || data.sub_list.length === 0) {
                        container.innerHTML = `<p class="text-muted">Chưa có mã đề nào. Vui lòng tạo mã đề mới.</p>`;
                        return;
                    }
                    data.sub_list.forEach(sublist => {
                        const courseName = sublist.questions.length > 0 &&
                            sublist.questions[0].list_question &&
                            sublist.questions[0].list_question.course
                            ? sublist.questions[0].list_question.course.course_name
                            : 'Không xác định';
                        const col = document.createElement('div');
                        col.className = 'col';
                        col.innerHTML = `
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">${sublist.title}</h5>
                            <p class="card-text"><strong>Môn học:</strong> ${courseName}</p>
                            <p class="card-text"><strong>Số câu hỏi:</strong> ${sublist.questions.length}</p>
                            <p class="card-text"><strong>Trộn câu hỏi:</strong> ${sublist.isShuffle ? 'Có' : 'Không'}</p>
                            <p class="card-text"><strong>Ngày tạo:</strong> ${new Date(sublist.created_at).toLocaleString()}</p>
                            <a href="/lecturer/chi_tiet_ma_de/${sublist.sub_list_id}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                `;
                        container.appendChild(col);
                    });
                })
                .catch(error => {
                    console.error('Lỗi khi lấy mã đề:', error);
                    container.innerHTML = `<p class="text-danger">Lỗi khi tải dữ liệu: ${error.message}. Vui lòng thử lại.</p>`;
                });
        }
        document.getElementById('courseFilter').addEventListener('change', function () {
            fetchListQuestions(this.value);
        });
        document.getElementById('sublistCourseFilter').addEventListener('change', function () {
            fetchSubLists(this.value);
        });
        document.getElementById('sublist-tab').addEventListener('shown.bs.tab', function () {
            fetchSubLists(document.getElementById('sublistCourseFilter').value);
        });
        fetchListQuestions();
    });
</script>
