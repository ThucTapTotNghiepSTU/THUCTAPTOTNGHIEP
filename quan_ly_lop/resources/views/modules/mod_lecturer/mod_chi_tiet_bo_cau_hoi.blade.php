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
                                <input type="text" class="form-control" id="sublist-title" required>
                            </div>

                            <div class="mb-3">
                                <label for="sublist-content" class="form-label">Nội dung</label>
                                <textarea class="form-control" id="sublist-content" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="sublist-type" class="form-label">Loại câu hỏi</label>
                                <select class="form-select" id="sublist-type" required>
                                    <option value="Trắc nghiệm">Trắc nghiệm</option>
                                    <option value="Tự luận">Tự luận</option>
                                </select>
                            </div>

                            <!-- Phần đáp án -->
                            <div class="mb-3" id="sublist-options-group">
                                <label class="form-label">Đáp án</label>
                                <input type="text" class="form-control mb-2" id="sublist-option-A" placeholder="Đáp án A">
                                <input type="text" class="form-control mb-2" id="sublist-option-B" placeholder="Đáp án B">
                                <input type="text" class="form-control mb-2" id="sublist-option-C" placeholder="Đáp án C">
                                <input type="text" class="form-control mb-2" id="sublist-option-D" placeholder="Đáp án D">
                            </div>

                            <!-- Chọn đáp án đúng -->
                            <div class="mb-3" id="sublist-correct-answer-group">
                                <label for="sublist-correct-answer" class="form-label">Đáp án đúng</label>
                                <select class="form-select" id="sublist-correct-answer" required>
                                    <option value="">-- Chọn --</option>
                                    <option value="A">Đáp án A</option>
                                    <option value="B">Đáp án B</option>
                                    <option value="C">Đáp án C</option>
                                    <option value="D">Đáp án D</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Tạo câu hỏi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Tạo Câu Hỏi -->
        <div class="modal fade" id="createQuestionModal" tabindex="-1" aria-labelledby="createQuestionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="createQuestionForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createQuestionModalLabel">Tạo câu hỏi mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="create-title" class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" id="create-title" required>
                            </div>

                            <div class="mb-3">
                                <label for="create-content" class="form-label">Nội dung</label>
                                <textarea class="form-control" id="create-content" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="create-type" class="form-label">Loại câu hỏi</label>
                                <select class="form-select" id="create-type" required>
                                    <option value="Trắc nghiệm">Trắc nghiệm</option>
                                    <option value="Tự luận">Tự luận</option>
                                </select>
                            </div>

                            <!-- Phần đáp án -->
                            <div class="mb-3" id="create-options-group">
                                <label class="form-label">Đáp án</label>
                                <input type="text" class="form-control mb-2" id="create-option-A" placeholder="Đáp án A">
                                <input type="text" class="form-control mb-2" id="create-option-B" placeholder="Đáp án B">
                                <input type="text" class="form-control mb-2" id="create-option-C" placeholder="Đáp án C">
                                <input type="text" class="form-control mb-2" id="create-option-D" placeholder="Đáp án D">
                            </div>

                            <!-- Chọn đáp án đúng -->
                            <div class="mb-3" id="create-correct-answer-group">
                                <label for="create-correct-answer" class="form-label">Đáp án đúng</label>
                                <select class="form-select" id="create-correct-answer" required>
                                    <option value="">-- Chọn --</option>
                                    <option value="A">Đáp án A</option>
                                    <option value="B">Đáp án B</option>
                                    <option value="C">Đáp án C</option>
                                    <option value="D">Đáp án D</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Nút Quay lại và Tạo câu hỏi -->
        <div class="d-flex gap-2 mb-4">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Quay lại</button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSublistModal">Tạo câu hỏi mới</button>
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
                            <input type="hidden" id="edit-question-id">

                            <div class="mb-3">
                                <label for="edit-title" class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" id="edit-title" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit-content" class="form-label">Nội dung</label>
                                <textarea class="form-control" id="edit-content" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="edit-type" class="form-label">Loại câu hỏi</label>
                                <select class="form-select" id="edit-type">
                                    <option value="Trắc nghiệm">Trắc nghiệm</option>
                                    <option value="Tự luận">Tự luận</option>
                                </select>
                            </div>

                            <!-- Phần đáp án -->
                            <div class="mb-3" id="edit-options-group">
                                <label class="form-label">Đáp án</label>
                                <input type="text" class="form-control mb-2" id="edit-option-A" placeholder="Đáp án A">
                                <input type="text" class="form-control mb-2" id="edit-option-B" placeholder="Đáp án B">
                                <input type="text" class="form-control mb-2" id="edit-option-C" placeholder="Đáp án C">
                                <input type="text" class="form-control mb-2" id="edit-option-D" placeholder="Đáp án D">
                            </div>

                            <!-- Chọn đáp án đúng -->
                            <div class="mb-3" id="edit-correct-answer-group">
                                <label for="edit-correct-answer" class="form-label">Đáp án đúng</label>
                                <select class="form-select" id="edit-correct-answer" required>
                                    <option value="">-- Chọn --</option>
                                    <option value="A">Đáp án A</option>
                                    <option value="B">Đáp án B</option>
                                    <option value="C">Đáp án C</option>
                                    <option value="D">Đáp án D</option>
                                </select>
                            </div>
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

                        questionList.innerHTML = ''; // Xóa danh sách cũ
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
                                            ${renderOptions(question.options)}
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

                        // Gán sự kiện cho các nút "Sửa"
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
                const modal = new bootstrap.Modal(document.getElementById('createQuestionModal'));
                modal.show();

                // Reset form khi mở modal
                document.getElementById('createQuestionForm').reset();
                document.getElementById('create-options-group').style.display = 'block';
                document.getElementById('create-correct-answer-group').style.display = 'block';
            };

            // Xử lý khi thay đổi loại câu hỏi trong modal tạo (createQuestionModal)
            document.getElementById('create-type').addEventListener('change', function () {
                const type = this.value;
                const optionsGroup = document.getElementById('create-options-group');
                const correctAnswerGroup = document.getElementById('create-correct-answer-group');
                if (type === 'Tự luận') {
                    optionsGroup.style.display = 'none';
                    correctAnswerGroup.style.display = 'none';
                } else {
                    optionsGroup.style.display = 'block';
                    correctAnswerGroup.style.display = 'block';
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

            // Xử lý form tạo câu hỏi (createQuestionModal)
            document.getElementById('createQuestionForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const type = document.getElementById('create-type').value;
                const data = {
                    list_question_id: listQuestionId,
                    title: document.getElementById('create-title').value,
                    content: document.getElementById('create-content').value,
                    type: type,
                };

                if (type === 'Trắc nghiệm') {
                    data.answer_a = document.getElementById('create-option-A').value;
                    data.answer_b = document.getElementById('create-option-B').value;
                    data.answer_c = document.getElementById('create-option-C').value;
                    data.answer_d = document.getElementById('create-option-D').value;
                    const correctAnswerIndex = document.getElementById('create-correct-answer').value;
                    data.correct_answer = correctAnswerIndex ? document.getElementById(`create-option-${correctAnswerIndex}`).value : null;
                }

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
                        if (!response.ok) throw new Error('Tạo câu hỏi thất bại');
                        return response.json();
                    })
                    .then(result => {
                        alert("Tạo câu hỏi thành công!");
                        bootstrap.Modal.getInstance(document.getElementById('createQuestionModal')).hide();
                        fetchQuestionList(); // Cập nhật danh sách câu hỏi
                    })
                    .catch(error => {
                        console.error(error);
                        alert("Có lỗi khi tạo câu hỏi!");
                    });
            });

            // Xử lý form tạo câu hỏi (createSublistModal)
            document.getElementById('createSublistForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const type = document.getElementById('sublist-type').value;
                const data = {
                    list_question_id: listQuestionId,
                    title: document.getElementById('sublist-title').value,
                    content: document.getElementById('sublist-content').value,
                    type: type,
                };

                if (type === 'Trắc nghiệm') {
                    data.answer_a = document.getElementById('sublist-option-A').value;
                    data.answer_b = document.getElementById('sublist-option-B').value;
                    data.answer_c = document.getElementById('sublist-option-C').value;
                    data.answer_d = document.getElementById('sublist-option-D').value;
                    const correctAnswerIndex = document.getElementById('sublist-correct-answer').value;
                    data.correct_answer = correctAnswerIndex ? document.getElementById(`sublist-option-${correctAnswerIndex}`).value : null;
                }

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
                        if (!response.ok) throw new Error('Tạo câu hỏi thất bại');
                        return response.json();
                    })
                    .then(result => {
                        alert("Tạo câu hỏi thành công!");
                        bootstrap.Modal.getInstance(document.getElementById('createSublistModal')).hide();
                        fetchQuestionList(); // Cập nhật danh sách câu hỏi
                    })
                    .catch(error => {
                        console.error(error);
                        alert("Có lỗi khi tạo câu hỏi: " + error.message);
                    });
            });

            function renderOptions(options) {
                if (!options || options.length === 0) return '';
                return `
                    <p><strong>Các lựa chọn:</strong></p>
                    <ul>
                        <li>${options[0]}</li>
                        <li>${options[1]}</li>
                        <li>${options[2]}</li>
                        <li>${options[3]}</li>
                    </ul>
                `;
            }

            function editQuestion(questionId) {
                const card = document.getElementById(`question-${questionId}`);
                const title = card.querySelector('.accordion-button').textContent.replace("Câu hỏi: ", "").trim();
                const content = card.querySelector('p:nth-of-type(1)').textContent.replace("Nội dung:", "").trim();
                const type = card.querySelector('p:nth-of-type(2)').textContent.replace("Loại:", "").trim();
                const correctAnswer = card.querySelector('p:nth-of-type(3)').textContent.replace("Đáp án đúng:", "").trim();
                const options = card.querySelectorAll('.accordion-body ul li');

                document.getElementById('edit-question-id').value = questionId;
                document.getElementById('edit-title').value = title;
                document.getElementById('edit-content').value = content;
                document.getElementById('edit-type').value = type;
                document.getElementById('edit-correct-answer').value = correctAnswer;

                if (type === "Tự luận") {
                    document.getElementById('edit-options-group').style.display = 'none';
                    document.getElementById('edit-correct-answer-group').style.display = 'none';
                } else {
                    document.getElementById('edit-options-group').style.display = 'block';
                    document.getElementById('edit-correct-answer-group').style.display = 'block';
                    options.forEach((option, index) => {
                        const optionValue = option.textContent.trim();
                        document.getElementById(`edit-option-${String.fromCharCode(65 + index)}`).value = optionValue;
                    });
                }

                new bootstrap.Modal(document.getElementById('editQuestionModal')).show();
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

            // Xử lý form chỉnh sửa câu hỏi
            document.getElementById('editQuestionForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const questionId = document.getElementById('edit-question-id').value;
                const type = document.getElementById('edit-type').value;
                const data = {
                    list_question_id: listQuestionId,
                    title: document.getElementById('edit-title').value,
                    content: document.getElementById('edit-content').value,
                    type: type,
                };

                if (type === 'Trắc nghiệm') {
                    data.answer_a = document.getElementById('edit-option-A').value;
                    data.answer_b = document.getElementById('edit-option-B').value;
                    data.answer_c = document.getElementById('edit-option-C').value;
                    data.answer_d = document.getElementById('edit-option-D').value;
                    const correctAnswerIndex = document.getElementById('edit-correct-answer').value;
                    data.correct_answer = correctAnswerIndex ? document.getElementById(`edit-option-${correctAnswerIndex}`).value : null;
                } else {
                    data.correct_answer = null;
                }

                fetch(`http://127.0.0.1:8000/api/questions/update/${questionId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Cập nhật thất bại');
                        return response.json();
                    })
                    .then(result => {
                        alert("Cập nhật thành công!");
                        const card = document.getElementById(`question-${questionId}`);
                        card.querySelector('.accordion-button').textContent = `Câu hỏi: ${data.title}`;
                        card.querySelector('p:nth-of-type(1)').textContent = `Nội dung: ${data.content}`;
                        card.querySelector('p:nth-of-type(2)').textContent = `Loại: ${data.type}`;
                        card.querySelector('p:nth-of-type(3)').textContent = `Đáp án đúng: ${data.correct_answer || 'Không có'}`;
                        if (type === 'Trắc nghiệm') {
                            const optionsList = card.querySelector('.accordion-body ul');
                            if (optionsList) {
                                optionsList.innerHTML = `
                                    <li>${data.answer_a}</li>
                                    <li>${data.answer_b}</li>
                                    <li>${data.answer_c}</li>
                                    <li>${data.answer_d}</li>
                                `;
                            } else {
                                card.querySelector('.accordion-body').insertAdjacentHTML('beforeend', `
                                    <p><strong>Các lựa chọn:</strong></p>
                                    <ul>
                                        <li>${data.answer_a}</li>
                                        <li>${data.answer_b}</li>
                                        <li>${data.answer_c}</li>
                                        <li>${data.answer_d}</li>
                                    </ul>
                                `);
                            }
                        } else {
                            const optionsSection = card.querySelector('.accordion-body p:has(strong:contains("Các lựa chọn"))');
                            if (optionsSection) {
                                optionsSection.nextElementSibling.remove();
                                optionsSection.remove();
                            }
                        }
                        bootstrap.Modal.getInstance(document.getElementById('editQuestionModal')).hide();
                    })
                    .catch(error => {
                        console.error(error);
                        alert("Có lỗi khi cập nhật câu hỏi!");
                    });
            });
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
