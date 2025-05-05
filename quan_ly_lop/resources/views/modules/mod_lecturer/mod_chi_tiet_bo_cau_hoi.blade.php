@extends('templates.template_lecture')

@section('main-content')
    <div class="container mt-4">
        <!-- Modal Tạo Câu Hỏi (trước đây là Tạo Mã Đề) -->
        <div class="modal fade" id="createSublistModal" tabindex="-1" aria-labelledby="createSublistModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="createSublistForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createSublistModalLabel">Tạo câu hỏi mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="sublist-title" class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" id="sublist-title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="sublist-content" class="form-label">Nội dung</label>
                                <textarea class="form-control" id="sublist-content" name="content" rows="3"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="sublist-type" class="form-label">Loại câu hỏi</label>
                                <select class="form-select" id="sublist-type" name="type" required>
                                    <option value="Trắc nghiệm">Trắc nghiệm</option>
                                    <option value="Tự luận">Tự luận</option>
                                </select>
                            </div>
                            <div class="mb-3" id="sublist-options-group">
                                <label class="form-label">Đáp án</label>
                                <input type="text" class="form-control mb-2" id="sublist-option-A" name="options[A][text]"
                                    placeholder="Đáp án A">
                                <input type="text" class="form-control mb-2" id="sublist-option-B" name="options[B][text]"
                                    placeholder="Đáp án B">
                                <input type="text" class="form-control mb-2" id="sublist-option-C" name="options[C][text]"
                                    placeholder="Đáp án C">
                                <input type="text" class="form-control mb-2" id="sublist-option-D" name="options[D][text]"
                                    placeholder="Đáp án D">
                            </div>
                            <div class="mb-3" id="sublist-correct-answer-group">
                                <label for="sublist-correct-answer" class="form-label">Đáp án đúng</label>
                                <select class="form-select" id="sublist-correct-answer" name="correct_answer" required>
                                    <option value="">-- Chọn --</option>
                                    <option value="A">Đáp án A</option>
                                    <option value="B">Đáp án B</option>
                                    <option value="C">Đáp án C</option>
                                    <option value="D">Đáp án D</option>
                                </select>
                            </div>
                            <div id="sublist-error-message" class="text-danger"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Tạo câu hỏi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Quay lại</button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSublistModal">Tạo
                câu hỏi mới</button>
        </div>

        <h2 class="mb-4 text-primary fw-bold">📋 Chi tiết câu hỏi</h2>
        <div class="card custom-card shadow-lg border-0 mb-4 rounded-4 bg-dark-subtle">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-black rounded-circle d-flex justify-content-center align-items-center"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-journal-text fs-2"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1 text-dark">Bộ câu hỏi của giảng viên</h5>
                        <p class="mb-0"><strong class="text-dark">Tên môn học:</strong> <span id="course-name"
                                class="text-muted">Đang tải...</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion" id="questionList"></div>

        <!-- Modal Sửa Câu Hỏi -->
        <!-- Modal Sửa Câu Hỏi -->
        <div class="modal fade" id="editQuestionModal" tabindex="-1" aria-labelledby="editQuestionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="editQuestionForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editQuestionModalLabel">Sửa câu hỏi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit-question-id" name="question_id">
                            <div class="mb-3">
                                <label for="edit-title" class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" id="edit-title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-content" class="form-label">Nội dung</label>
                                <textarea class="form-control" id="edit-content" name="content" rows="3"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit-type" class="form-label">Loại câu hỏi</label>
                                <select class="form-select" id="edit-type" name="type">
                                    <option value="Trắc nghiệm">Trắc nghiệm</option>
                                    <option value="Tự luận">Tự luận</option>
                                </select>
                            </div>
                            <div class="mb-3" id="edit-options-group">
                                <label class="form-label">Đáp án</label>
                                <input type="text" class="form-control mb-2" id="edit-option-A" name="options[A][text]"
                                    placeholder="Đáp án A">
                                <input type="text" class="form-control mb-2" id="edit-option-B" name="options[B][text]"
                                    placeholder="Đáp án B">
                                <input type="text" class="form-control mb-2" id="edit-option-C" name="options[C][text]"
                                    placeholder="Đáp án C">
                                <input type="text" class="form-control mb-2" id="edit-option-D" name="options[D][text]"
                                    placeholder="Đáp án D">
                            </div>
                            <div class="mb-3" id="edit-correct-answer-group">
                                <label for="edit-correct-answer" class="form-label">Đáp án đúng</label>
                                <select class="form-select" id="edit-correct-answer" name="correct_answer" required>
                                    <option value="">-- Chọn --</option>
                                    <option value="A">Đáp án A</option>
                                    <option value="B">Đáp án B</option>
                                    <option value="C">Đáp án C</option>
                                    <option value="D">Đáp án D</option>
                                </select>
                            </div>
                            <div id="edit-error-message" class="text-danger"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        let listQuestionId = "{{ $list_question_id }}";
        document.addEventListener("DOMContentLoaded", function () {
            const questionTypeSelect = document.getElementById('sublist-type');

            // Khôi phục trạng thái accordion
            const openAccordion = localStorage.getItem('openAccordion');
            if (openAccordion) {
                const accordion = document.querySelector(`#${openAccordion}`);
                if (accordion) {
                    accordion.classList.add('show');
                    accordion.previousElementSibling.querySelector('.accordion-button').classList.remove('collapsed');
                }
            }

            // Lưu trạng thái khi accordion được mở
            document.querySelectorAll('.accordion-collapse').forEach(collapse => {
                collapse.addEventListener('show.bs.collapse', function () {
                    localStorage.setItem('openAccordion', collapse.id);
                });
                collapse.addEventListener('hide.bs.collapse', function () {
                    localStorage.removeItem('openAccordion');
                });
            });

            // Tải danh sách câu hỏi
            fetchQuestionList();

            // Hàm tải danh sách câu hỏi
            function fetchQuestionList() {
                fetch(`http://127.0.0.1:8000/api/list-questions/detail/${listQuestionId}`)
                    .then(response => response.json())
                    .then(result => {
                        const questionList = document.getElementById("questionList");
                        if (!result.data) {
                            questionList.innerHTML = `<div class="alert alert-warning">Không tìm thấy danh sách câu hỏi!</div>`;
                            return;
                        }
                        const { course_id, course_name, questions } = result.data;
                        document.getElementById("course-name").textContent = course_name;
                        if (!questions || questions.length === 0) {
                            questionList.innerHTML = `<div class="alert alert-warning">Chưa có câu hỏi nào.</div>`;
                            return;
                        }
                        questionList.innerHTML = '';
                        questions.forEach((question, index) => {
                            const html = `
                                                        <div class="accordion-item mb-2" id="question-${question.question_id}">
                                                            <h2 class="accordion-header" id="heading${index}">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapse${index}">
                                                                    Câu hỏi: ${question.title}
                                                                </button>
                                                            </h2>
                                                            <div id="collapse${index}" class="accordion-collapse collapse" data-bs-parent="#questionList">
                                                                <div class="accordion-body">
                                                                    <p><strong>Nội dung:</strong> ${question.content}</p>
                                                                    <p><strong>Loại:</strong> ${question.type}</p>
                                                                    <p><strong>Đáp án đúng:</strong> ${question.correct_answer ?? 'Không có'}</p>
                                                                    ${renderOptions(question)}
                                                                    <div class="d-flex gap-2 mt-3">
                                                                        <button class="btn btn-warning btn-sm edit-button" data-question-id="${question.question_id}">Sửa</button>
                                                                        <button class="btn btn-danger btn-sm" onclick="deleteQuestion('${question.question_id}')">Xóa bỏ</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    `;
                            questionList.insertAdjacentHTML('beforeend', html);
                        });
                        const editButtons = document.querySelectorAll('.edit-button');
                        editButtons.forEach(button => {
                            button.addEventListener('click', function () {
                                const questionId = button.getAttribute('data-question-id');
                                editQuestion(questionId);
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Lỗi khi fetch dữ liệu:', error);
                        document.getElementById("questionList").innerHTML = `<div class="alert alert-danger">Lỗi khi tải dữ liệu.</div>`;
                    });
            }

            // Hàm mở modal tạo câu hỏi
            window.createQuestion = function () {
                const modal = new bootstrap.Modal(document.getElementById('createSublistModal')); // Sử dụng 'createSublistModal'
                modal.show();
                // Reset form khi mở modal
                document.getElementById('createSublistForm').reset();
                document.getElementById('sublist-options-group').style.display = 'block';
                document.getElementById('sublist-correct-answer-group').style.display = 'block';
            };

            // Xử lý khi thay đổi loại câu hỏi trong modal tạo
            document.getElementById('sublist-type').addEventListener('change', function () { // Sửa từ 'create-type' thành 'sublist-type'
                const type = this.value;
                const optionsGroup = document.getElementById('sublist-options-group');
                const correctAnswerGroup = document.getElementById('sublist-correct-answer-group');
                if (type === 'Tự luận') {
                    optionsGroup.style.display = 'none';
                    correctAnswerGroup.style.display = 'none';
                    document.getElementById('sublist-correct-answer').required = false;
                } else {
                    optionsGroup.style.display = 'block';
                    correctAnswerGroup.style.display = 'block';
                    document.getElementById('sublist-correct-answer').required = true;
                }
            });

            // Xử lý khi thay đổi loại câu hỏi trong modal tạo (createSublistModal)
            document.getElementById('sublist-type').addEventListener('change', function () {
                const type = this.value;
                const optionsGroup = document.getElementById('sublist-options-group');
                const correctAnswerGroup = document.getElementById('sublist-correct-answer-group');
                if (type === 'Tự luận') {
                    optionsGroup.style.display = 'none';
                    correctAnswerGroup.style.display = 'none';
                    document.getElementById('sublist-correct-answer').required = false;
                } else {
                    optionsGroup.style.display = 'block';
                    correctAnswerGroup.style.display = 'block';
                    document.getElementById('sublist-correct-answer').required = true;
                }
            });
            // Xử lý form tạo câu hỏi (createSublistModal)
            document.getElementById('createSublistForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);

                // Xử lý dữ liệu options cho câu hỏi trắc nghiệm
                if (data.type === 'Trắc nghiệm') {
                    const options = [
                        { text: data['options[A][text]'] || '' },
                        { text: data['options[B][text]'] || '' },
                        { text: data['options[C][text]'] || '' },
                        { text: data['options[D][text]'] || '' }
                    ].filter(opt => opt.text && opt.text.trim() !== '');

                    if (options.length < 2) {
                        document.getElementById('sublist-error-message').textContent = 'Câu hỏi trắc nghiệm cần ít nhất 2 đáp án không rỗng!';
                        return;
                    }
                    if (!data.correct_answer) {
                        document.getElementById('sublist-error-message').textContent = 'Vui lòng chọn một đáp án đúng!';
                        return;
                    }

                    data.options = options;
                    delete data['options[A][text]'];
                    delete data['options[B][text]'];
                    delete data['options[C][text]'];
                    delete data['options[D][text]'];
                } else {
                    delete data.options;
                    delete data.correct_answer;
                    delete data['options[A][text]'];
                    delete data['options[B][text]'];
                    delete data['options[C][text]'];
                    delete data['options[D][text]'];
                }

                data.list_question_id = listQuestionId;

                fetch('http://127.0.0.1:8000/api/questions/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => {
                        if (!response.ok) {
                            // Ném lỗi nếu trạng thái không phải 2xx
                            return response.json().then(errorData => {
                                throw new Error(`HTTP error ${response.status}: ${JSON.stringify(errorData)}`);
                            });
                        }
                        return response.json();
                    })
                    .then(result => {
                        if (result.errors) {
                            document.getElementById('sublist-error-message').textContent = Object.values(result.errors).flat().join(', ');
                        } else {
                            alert("Tạo câu hỏi thành công!");
                            bootstrap.Modal.getInstance(document.getElementById('createSublistModal')).hide();
                            form.reset();
                            fetchQuestionList();
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi tạo câu hỏi:', error);
                        document.getElementById('sublist-error-message').textContent = `Lỗi: ${error.message}`;
                    });
            });
            function renderOptions(question) {
                if (question.type !== 'Trắc nghiệm') return '';
                const options = question.options || [];
                if (options.length === 0) return '<p class="text-muted">Không có đáp án</p>';
                return `
                                            <p><strong>Các lựa chọn:</strong></p>
                                            <ul>
                                                ${options.map(option => `<li>${option.option_text}${option.is_correct ? ' (Đúng)' : ''}</li>`).join('')}
                                            </ul>
                                        `;
            }

            function editQuestion(questionId) {
                fetch(`http://127.0.0.1:8000/api/questions/getById/${questionId}`)
                    .then(response => response.json())
                    .then(question => {
                        document.getElementById('edit-question-id').value = questionId;
                        document.getElementById('edit-title').value = question.title;
                        document.getElementById('edit-content').value = question.content;
                        document.getElementById('edit-type').value = question.type;
                        if (question.type === 'Tự luận') {
                            document.getElementById('edit-options-group').style.display = 'none';
                            document.getElementById('edit-correct-answer-group').style.display = 'none';
                            document.getElementById('edit-correct-answer').required = false;
                        } else {
                            document.getElementById('edit-options-group').style.display = 'block';
                            document.getElementById('edit-correct-answer-group').style.display = 'block';
                            document.getElementById('edit-correct-answer').required = true;
                            document.getElementById('edit-option-A').value = question.options[0]?.option_text || '';
                            document.getElementById('edit-option-B').value = question.options[1]?.option_text || '';
                            document.getElementById('edit-option-C').value = question.options[2]?.option_text || '';
                            document.getElementById('edit-option-D').value = question.options[3]?.option_text || '';
                            const correctIndex = question.options.findIndex(option => option.is_correct);
                            document.getElementById('edit-correct-answer').value = correctIndex >= 0 ? ['A', 'B', 'C', 'D'][correctIndex] : '';
                        }
                        new bootstrap.Modal(document.getElementById('editQuestionModal')).show();
                    })
                    .catch(error => {
                        console.error('Lỗi khi lấy câu hỏi:', error);
                        alert('Lỗi khi lấy dữ liệu câu hỏi!');
                    });
            }

            window.deleteQuestion = function (questionId) {
                if (!confirm("Bạn có chắc chắn muốn xóa câu hỏi này không?")) return;
                fetch(`http://127.0.0.1:8000/api/questions/${questionId}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error("Lỗi khi xóa câu hỏi.");
                        document.getElementById(`question-${questionId}`).remove();
                    })
                    .catch(error => {
                        console.error(error);
                        alert("Xóa thất bại. Vui lòng thử lại!");
                    });
            };
            document.getElementById('edit-type').addEventListener('change', function () {
                const type = this.value;
                const optionsGroup = document.getElementById('edit-options-group');
                const correctAnswerGroup = document.getElementById('edit-correct-answer-group');
                if (type === 'Tự luận') {
                    optionsGroup.style.display = 'none';
                    correctAnswerGroup.style.display = 'none';
                    document.getElementById('edit-correct-answer').required = false;
                } else {
                    optionsGroup.style.display = 'block';
                    correctAnswerGroup.style.display = 'block';
                    document.getElementById('edit-correct-answer').required = true;
                }
            });
            // Xử lý form chỉnh sửa câu hỏi
            document.getElementById('editQuestionForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const form = this;
                const questionId = document.getElementById('edit-question-id').value;
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);
                if (data.type === 'Trắc nghiệm') {
                    const options = [
                        data['options[A][text]'],
                        data['options[B][text]'],
                        data['options[C][text]'],
                        data['options[D][text]']
                    ].filter(opt => opt && opt.trim() !== '');
                    if (options.length < 2) {
                        document.getElementById('edit-error-message').textContent = 'Câu hỏi trắc nghiệm cần ít nhất 2 đáp án không rỗng!';
                        return;
                    }
                    if (!data.correct_answer) {
                        document.getElementById('edit-error-message').textContent = 'Vui lòng chọn một đáp án đúng!';
                        return;
                    }
                } else {
                    delete data.options;
                    delete data.correct_answer;
                }
                data.list_question_id = listQuestionId;
                fetch(`http://127.0.0.1:8000/api/questions/update/${questionId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.errors) {
                            document.getElementById('edit-error-message').textContent = Object.values(result.errors).flat().join(', ');
                        } else {
                            alert("Cập nhật câu hỏi thành công!");
                            bootstrap.Modal.getInstance(document.getElementById('editQuestionModal')).hide();
                            form.reset();
                            fetchQuestionList();
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi cập nhật câu hỏi:', error);
                        document.getElementById('edit-error-message').textContent = 'Có lỗi khi cập nhật câu hỏi!';
                    });
            });
            window.deleteQuestion = function (questionId) {
                if (!confirm("Bạn có chắc chắn muốn xóa câu hỏi này không?")) return;
                fetch(`http://127.0.0.1:8000/api/questions/${questionId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error("Lỗi khi xóa câu hỏi.");
                        alert("Xóa câu hỏi thành công!");
                        fetchQuestionList();
                    })
                    .catch(error => {
                        console.error('Lỗi khi xóa câu hỏi:', error);
                        alert("Xóa thất bại. Vui lòng thử lại!");
                    });
            };
        });

    </script>

    <style>
        .sublist-card,
        .card-body,
        .card-title,
        .card-text {
            color: black !important;
            background-color: white !important;
            display: block !important;
            opacity: 1 !important;
            font-size: 16px !important;
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .custom-card .card-body {
            padding: 20px;
        }

        .custom-card .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .custom-card .text-muted {
            color: rgb(21, 25, 29);
        }

        .custom-card .bg-primary {
            background-color: #6c757d !important;
        }

        .custom-card i {
            font-size: 2rem;
        }

        .custom-card .card-body p {
            font-size: 1rem;
            line-height: 1.5;
        }

        .custom-card .card-title:hover {
            color: #ff7e5f;
        }
    </style>
@endsection
