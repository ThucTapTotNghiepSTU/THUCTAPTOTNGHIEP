<style>
    /* Thiết lập màu sắc chủ đạo */
    :root {
        --primary-color: #4e73df;
        --secondary-color: #1cc88a;
        --danger-color: #e74a3b;
        --light-color: #f8f9fc;
        --dark-color: #5a5c69;
        --border-color: #e3e6f0;
    }

    /* Thiết lập font chữ và màu nền chung */
    body {
        font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        background-color: #f8f9fc;
        color: #5a5c69;
    }

    /* Cải thiện container chính */
    .container.py-4 {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Cải thiện card chính */
    .card.shadow-lg {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card.shadow-lg:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
        border-radius: 0.75rem 0.75rem 0 0;
        padding: 1.25rem;
    }

    .card-header h4 {
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .card-body {
        padding: 2rem;
    }

    /* Cải thiện form elements */
    .form-label {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    /* Cải thiện buttons */
    .btn {
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #224abe 0%, var(--primary-color) 100%);
        transform: translateY(-2px);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--secondary-color) 0%, #13855c 100%);
        border: none;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #13855c 0%, var(--secondary-color) 100%);
        transform: translateY(-2px);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, #be2617 100%);
        border: none;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #be2617 0%, var(--danger-color) 100%);
        transform: translateY(-2px);
    }

    .btn-outline-secondary {
        border: 1px solid var(--border-color);
        color: var(--dark-color);
    }

    .btn-outline-secondary:hover {
        background-color: var(--light-color);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Cải thiện options section */
    #options-section {
        background-color: var(--light-color);
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .option {
        background-color: white;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.2s ease;
    }

    .option:hover {
        transform: translateX(5px);
    }

    /* Cải thiện temporary questions section */
    #temporaryQuestionsSection {
        background-color: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
    }

    #temporaryQuestionsSection h5 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--border-color);
    }

    #temporaryQuestionsList .mb-2 {
        background-color: var(--light-color);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    #temporaryQuestionsList .mb-2:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    #temporaryQuestionsList strong {
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    #temporaryQuestionsList p {
        margin-bottom: 0.5rem;
    }

    #temporaryQuestionsList ul {
        list-style-type: none;
        padding-left: 0;
    }

    #temporaryQuestionsList li {
        padding: 0.5rem 0;
        border-bottom: 1px dashed var(--border-color);
    }

    #temporaryQuestionsList li:last-child {
        border-bottom: none;
    }

    /* Cải thiện alerts */
    .alert {
        border-radius: 0.5rem;
        border: none;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background-color: rgba(28, 200, 138, 0.1);
        color: var(--secondary-color);
    }

    .alert-danger {
        background-color: rgba(231, 74, 59, 0.1);
        color: var(--danger-color);
    }

    /* Cải thiện form select */
    #courseSelect {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%235a5c69' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1rem;
        padding-right: 2.5rem;
    }

    /* Cải thiện radio buttons */
    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        margin-top: 0.25rem;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Cải thiện textarea */
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    /* Cải thiện responsive */
    @media (max-width: 768px) {
        .container.py-4 {
            padding: 1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
        }
    }

    /* Animation cho các phần tử */
    .animate__animated {
        animation-duration: 0.5s;
    }

    /* Cải thiện scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--light-color);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #224abe;
    }

    .card h5 {
        font-size: 1.2rem;
        font-weight: bold;
        color: #343a40;
    }

    .card p {
        margin-bottom: 0.5rem;
    }

    .card .card-body {
        padding: 1.25rem;
    }

    .card-hover {
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    /* Dải màu đầu thẻ */
    .card-hover::before {
        content: "";
        cursor: pointer;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background-color: #007bff;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        transition: background-color 0.3s ease;
    }

    /* Mũi tên */
    .card-hover::after {
        content: '→';
        position: absolute;
        bottom: 16px;
        right: 16px;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.3s ease, transform 0.3s ease, color 0.3s ease;
        font-size: 1.8rem;
        color: #007bff;
        pointer-events: none;
    }

    /* Hover đồng bộ cả 2 */
    .card-hover:hover::before {
        background-color: #28a745;
    }

    .card-hover:hover::after {
        opacity: 1;
        transform: translateY(0);
        color: #28a745;
    }

    .card-text strong {
        color: #495057;
    }

    #courseFilter {
        width: 250px;
        border-color: #343a40;
    }

    #courseSelect {
        width: fit-content;
        border-color: #343a40;
    }

    #startCreateQuestion {
        width: 250px;
    }
</style>
<style>
    /* Giữ nguyên CSS */
</style>
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-start">
        <div class="col-md-10">
            @auth
                <form id="createListQuestionForm" novalidate>
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
                        <input type="text" class="form-control mt-2" id="topic_input" name="topic" style="display: none;"
                            placeholder="Nhập chủ đề mới">
                    </div>

                    <button type="submit" id="startCreateQuestion" class="btn btn-primary">Tạo bộ câu hỏi</button>
                </form>

                <div id="temporaryQuestionsSection" class="mb-4" style="display: none;">
                    <h5>Câu hỏi đã lưu tạm thời:</h5>
                    <div id="temporaryQuestionsList"></div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow-lg animate__animated animate__fadeIn" style="display: none;">
                            <div class="card-header bg-primary text-white text-center">
                                <h4 class="mb-0">Tạo Câu Hỏi Trắc Nghiệm</h4>
                            </div>
                            <div class="card-body p-4">
                                <form id="createQuestionForm" action="{{ route('questions.store') }}" method="POST">
                                    @csrf
                                    <!-- Tiêu đề câu hỏi -->
                                    <div class="mb-3">
                                        <label for="title" class="form-label fw-bold">Tiêu Đề Câu Hỏi</label>
                                        <input type="text" id="title" name="title" class="form-control"
                                            placeholder="Nhập tiêu đề câu hỏi" required>
                                    </div>
                                    <!-- Nội dung câu hỏi -->
                                    <div class="mb-3">
                                        <label for="content" class="form-label fw-bold">Nội Dung Câu Hỏi</label>
                                        <textarea id="content" name="content" class="form-control" rows="4"
                                            placeholder="Nhập nội dung câu hỏi" required></textarea>
                                    </div>
                                    <!-- Loại câu hỏi -->
                                    <div class="mb-3">
                                        <label for="type" class="form-label fw-bold">Loại Câu Hỏi</label>
                                        <select id="type" name="type" class="form-select" required>
                                            <option value="Trắc nghiệm">Trắc nghiệm</option>
                                            <option value="Tự luận">Tự luận</option>
                                        </select>
                                    </div>

                                    <!-- Đáp án (Hiển thị nếu là Trắc nghiệm) -->
                                    <div id="options-section" class="mb-3">
                                        <label class="form-label fw-bold">Đáp Án</label>
                                        <div id="options-container">
                                            <div class="d-flex align-items-center mb-2 option">
                                                <input type="text" name="options[0][option_text]" class="form-control me-2"
                                                    placeholder="Nhập đáp án 1" required>
                                                <input type="radio" name="correct_answer" value="0"
                                                    class="form-check-input">
                                                <span class="ms-2">Đáp án đúng</span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary mt-2" onclick="addOption()">+
                                            Thêm Lựa Chọn</button>
                                    </div>

                                    <!-- Nút gửi form -->
                                    <div class="text-center">
                                        <button type="button" id="saveQuestion" class="btn btn-primary w-100 mt-3">Tạo Thêm
                                            Câu Hỏi</button>
                                        <button type="button" id="finishCreating" class="btn btn-success w-100 mt-3">Hoàn
                                            Thành</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <div class="container mt-3">
        <div class="flex justify-content-between align-items-center mb-4">
            <h3 class="mb-4">Danh sách bộ câu hỏi</h3>
            <select name="course_id" id="courseFilter" class="form-select">
                <option value="all" selected>Tất cả</option>
                @foreach($courses as $course)
                    <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                @endforeach
            </select>
        </div>
        <div id="list-question-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 cursor-pointer">
            <!-- Cards sẽ được render ở đây -->
        </div>
    </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="lecturer-id" content="{{ Auth::user()->lecturer_id }}">
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const temporaryQuestionsList = document.getElementById("temporaryQuestionsList");
        const saveQuestionButton = document.getElementById("saveQuestion");
        const finishCreatingButton = document.getElementById("finishCreating");
        const questionForm = document.querySelector('.card.shadow-lg');
        const temporaryQuestionsSection = document.getElementById('temporaryQuestionsSection');
        let optionCount = 1;
        const lecturerId = document.querySelector('meta[name="lecturer-id"]').getAttribute('content');
        const courseFilter = document.getElementById("courseFilter");
        const courseToCreateQuestion = document.getElementById("course_id");
        const submitBtn = document.getElementById("startCreateQuestion");

        // Kiểm tra xem đã có list_question_id chưa
        const existingListQuestionId = localStorage.getItem("list_question_id");
        if (existingListQuestionId) {
            questionForm.style.display = 'block';
            temporaryQuestionsSection.style.display = 'block';
            renderTemporaryQuestions();
        }

        // Disable the submit button initially
        if (submitBtn) {
            submitBtn.disabled = true;
        }

        // Khi chọn môn học, lấy danh sách topic tương ứng
        // Khi chọn môn học, lấy danh sách topic tương ứng
        if (courseToCreateQuestion) {
            courseToCreateQuestion.addEventListener('change', function () {
                const courseId = this.value;
                const topicContainer = document.getElementById('topic_container');
                const topicSelect = document.getElementById('topic_select');
                const topicInput = document.getElementById('topic_input');

                if (courseId) {
                    topicContainer.style.display = 'block';
                    fetch(`/api/list-questions/topics/${courseId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Reset topic select
                            topicSelect.innerHTML = '<option value="">Chọn chủ đề</option>';
                            if (data.topics && data.topics.length > 0) {
                                data.topics.forEach(topic => {
                                    const option = document.createElement('option');
                                    option.value = topic;
                                    option.textContent = topic;
                                    topicSelect.appendChild(option);
                                });
                            }
                            // Thêm tùy chọn "Nhập chủ đề mới" vào cuối
                            const newOption = document.createElement('option');
                            newOption.value = 'new';
                            newOption.textContent = 'Nhập chủ đề mới';
                            topicSelect.appendChild(newOption);
                        })
                        .catch(error => {
                            console.error('Lỗi khi lấy danh sách topic:', error);
                            alert('Lỗi khi tải chủ đề. Vui lòng thử lại sau. Chi tiết lỗi: ' + error.message);
                            topicSelect.innerHTML = '<option value="">Không có chủ đề</option><option value="new">Nhập chủ đề mới</option>';
                        });

                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                } else {
                    topicContainer.style.display = 'none';
                    topicSelect.innerHTML = '<option value="">Chọn chủ đề</option><option value="new">Nhập chủ đề mới</option>';
                    topicInput.style.display = 'none';
                    if (submitBtn) {
                        submitBtn.disabled = true;
                    }
                }
            });
        }

        // Xử lý khi người dùng thay đổi lựa chọn trong topic select
        document.getElementById('topic_select').addEventListener('change', function () {
            const topicInput = document.getElementById('topic_input');
            if (this.value === 'new') {
                topicInput.style.display = 'block';
                topicInput.focus();
            } else {
                topicInput.style.display = 'none';
                topicInput.value = '';
            }
        });

        function fetchListQuestions(courseId = "null") {
            let url = `/api/list-questions/${lecturerId}`;
            if (courseId !== "null" && courseId !== "all") {
                url += `?course_id=${courseId}`;
            }
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById("list-question-container");
                    container.innerHTML = "";
                    if (data.length === 0) {
                        container.innerHTML = `<p class="text-muted">Chưa có bộ câu hỏi nào cho môn học này.</p>`;
                        return;
                    }
                    data.forEach(item => {
                        const card = document.createElement("div");
                        card.className = "col";
                        const lecturerName = item.lecturer?.fullname || "Không rõ";
                        const courseName = item.course?.course_name || "Không rõ";
                        card.innerHTML = `
                        <div class="card h-100 shadow-sm card-hover position-relative" data-id="${item.list_question_id}">
                            <div class="card-body">
                                <p class="card-text"><strong>Môn học:</strong> ${courseName}</p>
                                <p class="card-text"><strong>Giảng viên:</strong> ${lecturerName}</p>
                                <p class="card-text"><small class="text-muted">Tạo lúc: ${new Date(item.created_at).toLocaleString()}</small></p>
                            </div>
                        </div>
                    `;
                        const cardElement = card.querySelector('.card');
                        cardElement.addEventListener("click", () => {
                            const listQuestionId = cardElement.getAttribute("data-id");
                            window.location.href = `/lecturer/chi_tiet_bo_cau_hoi/${listQuestionId}`;
                        });
                        container.appendChild(card);
                    });
                })
                .catch(error => {
                    console.error("Lỗi khi lấy danh sách câu hỏi:", error);
                    document.getElementById("list-question-container").innerHTML =
                        `<p class="text-danger">Không thể tải dữ liệu. Vui lòng thử lại sau.</p>`;
                });
        }

        if (courseFilter) {
            courseFilter.addEventListener("change", function () {
                const selectedValue = this.value;
                const courseId = selectedValue === "all" ? "null" : selectedValue;
                fetchListQuestions(courseId);
            });
        }

        fetchListQuestions(); // Load all courses initially

        function renderTemporaryQuestions() {
            let savedQuestions;
            try {
                savedQuestions = JSON.parse(localStorage.getItem('questions')) || [];
            } catch (error) {
                console.error('Error parsing questions from localStorage:', error);
                savedQuestions = [];
            }

            temporaryQuestionsList.innerHTML = '';

            if (savedQuestions.length > 0) {
                savedQuestions.forEach((question, index) => {
                    const questionDiv = document.createElement('div');
                    questionDiv.classList.add('mb-2');

                    const optionsHtml = question.options && Array.isArray(question.options)
                        ? `<ul>${question.options.map(option =>
                            `<li>${option.option_text} ${option.is_correct ? '(Đúng)' : ''}</li>`
                        ).join('')}</ul>`
                        : '';

                    questionDiv.innerHTML = `
                    <div>
                        <strong>${question.title}</strong>
                        <p>${question.content}</p>
                        <p><em>Loại câu hỏi: ${question.type}</em></p>
                        ${optionsHtml}
                        <button class="btn btn-danger btn-sm delete-question" data-index="${index}">
                            Xóa câu hỏi
                        </button>
                        <hr>
                    </div>
                `;
                    temporaryQuestionsList.appendChild(questionDiv);
                });

                document.querySelectorAll('.delete-question').forEach(button => {
                    button.addEventListener('click', function () {
                        const index = parseInt(this.getAttribute('data-index'));
                        deleteTemporaryQuestion(index);
                    });
                });
            } else {
                temporaryQuestionsList.innerHTML = "<p>Không có câu hỏi tạm thời nào.</p>";
            }
        }

        function deleteTemporaryQuestion(index) {
            const savedQuestions = JSON.parse(localStorage.getItem('questions')) || [];
            savedQuestions.splice(index, 1);
            localStorage.setItem('questions', JSON.stringify(savedQuestions));
            renderTemporaryQuestions();
        }

        function resetForm() {
            document.getElementById('createQuestionForm').reset();
            document.getElementById('options-container').innerHTML = `
            <div class="d-flex align-items-center mb-2 option">
                <input type="text" name="options[0][option_text]" class="form-control me-2"
                    placeholder="Nhập đáp án 1" required>
                <input type="radio" name="correct_answer" value="0"
                    class="form-check-input">
                <span class="ms-2">Đáp án đúng</span>
            </div>
        `;
            optionCount = 1;
        }

        document.getElementById('type').addEventListener('change', function () {
            const optionsSection = document.getElementById('options-section');
            optionsSection.style.display = this.value === 'Trắc nghiệm' ? 'block' : 'none';
        });

        function addOption() {
            const container = document.getElementById('options-container');
            const optionDiv = document.createElement('div');
            optionDiv.classList.add('d-flex', 'align-items-center', 'mb-2', 'option');
            optionDiv.innerHTML = `
            <input type="text" name="options[${optionCount}][option_text]" class="form-control me-2"
                placeholder="Nhập đáp án ${optionCount + 1}" required>
            <input type="radio" name="correct_answer" value="${optionCount}" class="form-check-input">
            <span class="ms-2">Đáp án đúng</span>
        `;
            container.appendChild(optionDiv);
            optionCount++;
        }

        window.addOption = addOption;

        document.getElementById('createListQuestionForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const courseId = document.getElementById('course_id').value;
            const topicSelect = document.getElementById('topic_select');
            const topicInput = document.getElementById('topic_input');

            // Lấy giá trị topic
            let topic;
            if (topicSelect.value === 'new') {
                topic = topicInput.value.trim();
            } else {
                topic = topicSelect.value;
            }

            if (!courseId || !topic) {
                alert('Vui lòng chọn môn học và chủ đề!');
                return;
            }

            fetch('/api/list-questions/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    course_id: courseId,
                    topic: topic,
                    lecturer_id: lecturerId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let message = 'Tạo bộ câu hỏi thành công!';
                        if (data.new_topic_created) {
                            message += ' Chủ đề mới đã được tạo: ' + topic;
                        }
                        alert(message);
                        localStorage.setItem('list_question_id', data.list_question_id);
                        questionForm.style.display = 'block';
                        temporaryQuestionsSection.style.display = 'block';
                        renderTemporaryQuestions();
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi tạo bộ câu hỏi:', error);
                    alert('Đã xảy ra lỗi khi tạo bộ câu hỏi!');
                });
        });

        if (saveQuestionButton) {
            saveQuestionButton.addEventListener('click', function () {
                const listQuestionId = localStorage.getItem("list_question_id");
                if (!listQuestionId) {
                    alert("Vui lòng bắt đầu tạo bộ câu hỏi trước!");
                    return;
                }

                const title = document.getElementById('title').value;
                const content = document.getElementById('content').value;
                let type = document.getElementById('type').value;

                if (!title || !content) {
                    alert("Vui lòng nhập đầy đủ tiêu đề và nội dung câu hỏi!");
                    return;
                }

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
                    if (options.length < 2) {
                        alert("Câu hỏi trắc nghiệm cần ít nhất 2 lựa chọn!");
                        return;
                    }

                    if (!hasCorrectAnswer) {
                        alert("Vui lòng chọn đáp án đúng!");
                        return;
                    }
                }

                const question = { title, content, type, options };
                let savedQuestions = JSON.parse(localStorage.getItem('questions')) || [];
                savedQuestions.push(question);
                localStorage.setItem('questions', JSON.stringify(savedQuestions));
                alert("Câu hỏi đã được lưu tạm thời.");
                resetForm();
                renderTemporaryQuestions();
            });
        } else {
            console.error("Save button not found");
        }

        if (finishCreatingButton) {
            finishCreatingButton.addEventListener('click', function () {
                let savedQuestions = JSON.parse(localStorage.getItem('questions')) || [];
                if (savedQuestions.length === 0) {
                    alert("Chưa có câu hỏi nào được lưu!");
                    return;
                }
                const listQuestionId = localStorage.getItem("list_question_id");
                if (!listQuestionId) {
                    alert("Có lỗi xảy ra! Không tìm thấy danh sách câu hỏi.");
                    return;
                }

                // Kiểm tra dữ liệu trước khi gửi
                let isValid = true;
                savedQuestions.forEach((question, index) => {
                    if (!question.title || !question.content || !question.type) {
                        alert(`Câu hỏi ${index + 1} thiếu thông tin bắt buộc (tiêu đề, nội dung hoặc loại câu hỏi)!`);
                        isValid = false;
                    }
                    if (question.type === 'Trắc nghiệm') {
                        if (!question.options || question.options.length < 2) {
                            alert(`Câu hỏi ${index + 1} cần ít nhất 2 lựa chọn!`);
                            isValid = false;
                        } else {
                            const hasCorrectAnswer = question.options.some(option => option.is_correct);
                            if (!hasCorrectAnswer) {
                                alert(`Câu hỏi ${index + 1} cần có ít nhất 1 đáp án đúng!`);
                                isValid = false;
                            }
                        }
                    }
                });

                if (!isValid) return;

                finishCreatingButton.disabled = true;
                finishCreatingButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';

                // Gửi dữ liệu lên server
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
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw new Error(JSON.stringify(err));
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            localStorage.removeItem('questions');
                            localStorage.removeItem('list_question_id');
                            alert("Tất cả câu hỏi đã được lưu thành công!");
                            window.location.href = '/createQuestion';
                        } else {
                            alert("Lỗi: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Lỗi:", error);
                        alert("Có lỗi xảy ra: " + error.message);
                    })
                    .finally(() => {
                        finishCreatingButton.disabled = false;
                        finishCreatingButton.innerHTML = 'Hoàn Thành';
                    });
            });
        } else {
            console.error("Finish button not found");
        }
    });
</script>
