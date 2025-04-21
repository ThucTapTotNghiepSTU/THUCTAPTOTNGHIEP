<style>
/* CSS cải tiến cho modal và các thành phần */
.modal-xl {
    max-width: 90%; /* Tăng chiều rộng tối đa của modal trên desktop */
}

.modal-body {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
}

.modal-column {
    flex: 1;
    min-width: 300px; /* Đảm bảo cột không quá hẹp */
}

.modal-section {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background-color: #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.modal-section h6 {
    margin-bottom: 15px;
    color: #343a40;
    font-weight: 600;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.form-select,
.form-check-input {
    margin-top: 5px;
}

.question-list {
    max-height: 200px;
    overflow-y: auto;
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    background-color: #f8f9fa;
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
}

.topic-random-input {
    margin-left: 10px;
    width: 80px;
    padding: 5px;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    padding-top: 15px;
}

.form-check {
    margin-bottom: 10px;
}

/* Responsive cho điện thoại */
@media (max-width: 768px) {
    .modal-xl {
        max-width: 100%; /* Trên điện thoại, modal chiếm toàn bộ chiều rộng */
    }

    .modal-body {
        flex-direction: column;
        gap: 0;
        padding: 15px;
    }

    .modal-column {
        width: 100%;
        min-width: unset; /* Bỏ giới hạn min-width trên điện thoại */
    }

    .modal-section {
        margin-bottom: 15px;
        padding: 10px;
    }

    .question-list {
        max-height: 150px; /* Giảm chiều cao trên điện thoại */
    }

    .topic-random-input {
        width: 60px; /* Giảm kích thước input trên điện thoại */
    }
}

/* Tăng khoảng cách và cải thiện giao diện tổng thể */
.modal-header {
    border-bottom: 1px solid #dee2e6;
    padding: 15px 20px;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
}

.btn-close {
    padding: 10px;
}

.form-control:focus,
.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
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
        <div class="modal fade" id="createSublistModal" tabindex="-1" aria-labelledby="createSublistModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl"> <!-- Thay modal-lg thành modal-xl để rộng hơn -->
                <div class="modal-content">
                    <form id="createSublistForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createSublistModalLabel">Tạo đề thi mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body d-flex flex-wrap gap-3 p-4"> <!-- Sử dụng flexbox và padding lớn hơn -->
                            <!-- Cột 1 -->
                            <div class="modal-column">
                                <!-- Phần 0: Nhập tiêu đề mã đề -->
                                <div class="modal-section mb-4 p-3 border rounded bg-white shadow-sm">
                                    <h6 class="mb-3">Tiêu đề mã đề</h6>
                                    <div class="mb-3">
                                        <label for="modal_sublist_title" class="form-label">Mã đề thi <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="modal_sublist_title"
                                            name="modal_sublist_title" required placeholder="Nhập tiêu đề mã đề">
                                        <small class="form-text text-muted">Tiêu đề giúp bạn nhận diện mã đề dễ dàng
                                            hơn.</small>
                                    </div>
                                </div>

                                <!-- Phần 1: Chọn môn học và chủ đề -->
                                <div class="modal-section mb-4 p-3 border rounded bg-white shadow-sm">
                                    <h6 class="mb-3">Chọn môn học và chủ đề</h6>
                                    <div class="mb-3">
                                        <label for="modal_course_id" class="form-label">Môn học <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="modal_course_id" name="modal_course_id" required>
                                            <option value="">Chọn môn học</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Chọn môn học để lấy danh sách bộ câu
                                            hỏi.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="modal_topic" class="form-label">Chủ đề</label>
                                        <select class="form-select" id="modal_topic" name="modal_topic">
                                            <option value="">Tất cả</option>
                                        </select>
                                        <small class="form-text text-muted">Chọn chủ đề để lọc bộ câu hỏi (tùy
                                            chọn).</small>
                                    </div>
                                </div>

                                <!-- Phần 2: Chọn bộ câu hỏi -->
                                <div class="modal-section mb-4 p-3 border rounded bg-white shadow-sm">
                                    <h6 class="mb-3">Chọn bộ câu hỏi</h6>
                                    <div class="mb-3">
                                        <label for="modal_list_question" class="form-label">Bộ câu hỏi <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="modal_list_question" name="modal_list_question"
                                            required>
                                            <option value="">Chọn bộ câu hỏi</option>
                                        </select>
                                        <small class="form-text text-muted">Chọn bộ câu hỏi để xem danh sách câu
                                            hỏi.</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Cột 2 -->
                            <div class="modal-column">
                                <!-- Phần 3: Chọn câu hỏi -->
                                <div class="modal-section mb-4 p-3 border rounded bg-white shadow-sm">
                                    <h6 class="mb-3">Chọn câu hỏi</h6>
                                    <div id="question_list" class="question-list">
                                        <p class="text-muted">Vui lòng chọn bộ câu hỏi để hiển thị danh sách câu hỏi.</p>
                                    </div>
                                </div>

                                <!-- Phần 4: Random theo chủ đề -->
                                <div class="modal-section mb-4 p-3 border rounded bg-white shadow-sm">
                                    <h6 class="mb-3">Random câu hỏi theo chủ đề</h6>
                                    <div id="random_topics" class="random-topics-container"></div>
                                    <small class="form-text text-muted">Nhập số lượng câu hỏi muốn chọn ngẫu nhiên theo từng
                                        chủ đề.</small>
                                </div>

                                <!-- Phần 5: Cài đặt trộn câu hỏi -->
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Tạo mã đề</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="mb-2">
            <button type="button" class="btn btn-primary mb-4" onclick="createSublist()">Tạo đề thi</button>
        </div>
        <!-- Tabs -->
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
            <!-- Tab 1: Danh sách bộ câu hỏi -->
            <div class="tab-pane fade show active" id="list-question" role="tabpanel" aria-labelledby="list-question-tab">
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
                </div>
            </div>

            <!-- Tab 2: Danh sách mã đề -->
            <div class="tab-pane fade" id="sublist" role="tabpanel" aria-labelledby="sublist-tab">
                <div class="flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-4">Danh sách mã đề</h3>
                    <select name="course_id" id="sublistCourseFilter" class="form-select">
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
    // Hàm toàn cục để mở modal
    function createSublist() {
        const modal = new bootstrap.Modal(document.getElementById('createSublistModal'));
        modal.show();
    }

    document.addEventListener("DOMContentLoaded", function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const lecturerId = document.querySelector('meta[name="lecturer-id"]').getAttribute('content');

        // Khi chọn môn học trong modal
        document.getElementById('modal_course_id').addEventListener('change', function () {
            const courseId = this.value;
            const topicSelect = document.getElementById('modal_topic');
            const listQuestionSelect = document.getElementById('modal_list_question');
            const randomTopics = document.getElementById('random_topics');

            if (courseId) {
                fetch(`/api/list-questions/${lecturerId}?course_id=${courseId}`)
                    .then(response => response.json())
                    .then(data => {
                        const topics = [...new Set(data.map(item => item.topic))];
                        topicSelect.innerHTML = '<option value="">Tất cả</option>';
                        topics.forEach(topic => {
                            const option = document.createElement('option');
                            option.value = topic;
                            option.textContent = topic;
                            topicSelect.appendChild(option);
                        });

                        listQuestionSelect.innerHTML = '<option value="">Chọn bộ câu hỏi</option>';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.list_question_id;
                            option.textContent = `${item.topic} (${item.course.course_name})`;
                            listQuestionSelect.appendChild(option);
                        });

                        randomTopics.innerHTML = '';
                        topics.forEach(topic => {
                            const div = document.createElement('div');
                            div.className = 'random-topic-item';
                            div.innerHTML = `
                            <label>${topic}</label>
                            <input type="number" class="topic-random-input form-control form-control-sm" data-topic="${topic}" min="0" placeholder="Số câu">
                        `;
                            randomTopics.appendChild(div);
                        });
                    })
                    .catch(error => console.error('Lỗi khi lấy dữ liệu:', error));
            }
        });

        // Khi chọn topic trong modal
        document.getElementById('modal_topic').addEventListener('change', function () {
            const courseId = document.getElementById('modal_course_id').value;
            const topic = this.value;
            const listQuestionSelect = document.getElementById('modal_list_question');

            fetch(`/api/list-questions/${lecturerId}?course_id=${courseId}`)
                .then(response => response.json())
                .then(data => {
                    listQuestionSelect.innerHTML = '<option value="">Chọn bộ câu hỏi</option>';
                    data.forEach(item => {
                        if (!topic || item.topic === topic) {
                            const option = document.createElement('option');
                            option.value = item.list_question_id;
                            option.textContent = `${item.topic} (${item.course.course_name})`;
                            listQuestionSelect.appendChild(option);
                        }
                    });
                })
                .catch(error => console.error('Lỗi khi lấy dữ liệu:', error));
        });

        // Khi chọn ListQuestion
        document.getElementById('modal_list_question').addEventListener('change', function () {
            const listQuestionId = this.value;
            if (listQuestionId) {
                fetch(`/api/list-questions/detail/${listQuestionId}`)
                    .then(response => response.json())
                    .then(detail => {
                        const questionList = document.getElementById('question_list');
                        questionList.innerHTML = '';
                        if (detail.data.questions.length === 0) {
                            questionList.innerHTML = '<p class="text-muted">Không có câu hỏi nào trong bộ này.</p>';
                            return;
                        }
                        detail.data.questions.forEach(q => {
                            const div = document.createElement('div');
                            div.className = 'form-check';
                            div.innerHTML = `
                            <input class="form-check-input question-checkbox" type="checkbox" data-id="${q.question_id}" data-list-id="${listQuestionId}" id="question-${q.question_id}">
                            <label class="form-check-label" for="question-${q.question_id}">${q.title} (${q.type})</label>
                        `;
                            questionList.appendChild(div);
                        });

                        document.querySelectorAll('.question-checkbox').forEach(checkbox => {
                            checkbox.addEventListener('change', function () {
                                let selectedQuestions = JSON.parse(localStorage.getItem('selectedQuestions')) || [];
                                const questionId = this.getAttribute('data-id');
                                const listQuestionId = this.getAttribute('data-list-id');
                                if (this.checked) {
                                    selectedQuestions.push({ question_id: questionId, list_question_id: listQuestionId });
                                } else {
                                    selectedQuestions = selectedQuestions.filter(item => item.question_id !== questionId);
                                }
                                localStorage.setItem('selectedQuestions', JSON.stringify(selectedQuestions));
                            });
                        });
                    })
                    .catch(error => console.error('Lỗi khi lấy câu hỏi:', error));
            }
        });

        // Xử lý form tạo mã đề
        document.getElementById('createSublistForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            // Lấy giá trị title từ trường nhập
            const title = document.getElementById('modal_sublist_title').value;
            const listQuestionId = document.getElementById('modal_list_question').value;
            const isShuffle = document.getElementById('isShuffle').checked;
            let selectedQuestions = JSON.parse(localStorage.getItem('selectedQuestions')) || [];
            const randomInputs = document.querySelectorAll('.topic-random-input');
            for (const input of randomInputs) {
                const topic = input.getAttribute('data-topic');
                const count = parseInt(input.value) || 0;
                if (count > 0) {
                    const response = await fetch(`/api/list-questions/${lecturerId}?course_id=${document.getElementById('modal_course_id').value}`);
                    const data = await response.json();
                    const topicItems = data.filter(item => item.topic === topic);
                    for (const item of topicItems) {
                        const detailResponse = await fetch(`/api/list-questions/detail/${item.list_question_id}`);
                        const detail = await detailResponse.json();
                        const shuffled = detail.data.questions.sort(() => 0.5 - Math.random());
                        const selected = shuffled.slice(0, count).map(q => ({
                            question_id: q.question_id,
                            list_question_id: item.list_question_id
                        }));
                        selectedQuestions = [...selectedQuestions, ...selected];
                    }
                }
            }

            localStorage.setItem('selectedQuestions', JSON.stringify(selectedQuestions));

            if (selectedQuestions.length === 0) {
                alert('Vui lòng chọn ít nhất một câu hỏi!');
                return;
            }

            if (!listQuestionId) {
                alert('Vui lòng chọn một bộ câu hỏi!');
                return;
            }

            if (!title) {
                alert('Vui lòng nhập tiêu đề mã đề!');
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
                        title: title, // Sử dụng giá trị title từ input
                        isShuffle,
                        list_question_id: listQuestionId,
                        question_ids: selectedQuestions.map(q => q.question_id)
                    })
                });

                const data = await res.json();
                if (res.ok) {
                    alert("✅ Tạo mã đề thành công!");
                    localStorage.removeItem('selectedQuestions');
                    document.getElementById('createSublistForm').reset();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createSublistModal'));
                    modal.hide();
                    // Không gọi fetchSubLists ngay, đợi người dùng mở tab mã đề
                } else {
                    alert("❌ Lỗi tạo mã đề: " + (data.message || 'Có lỗi xảy ra.'));
                }
            } catch (err) {
                console.error(err);
                alert("❌ Lỗi kết nối đến server.");
            }
        });

        // Fetch danh sách bộ câu hỏi
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
                    console.error('Lỗi khi lấy bộ câu hỏi:', error);
                    container.innerHTML = `<p class="text-danger">Lỗi khi tải dữ liệu.</p>`;
                });
        }

        // Fetch danh sách mã đề
        function fetchSubLists(courseId = "all") {
            console.log('Fetching sublists for lecturerId:', lecturerId, 'courseId:', courseId);
            const container = document.getElementById("sublist-container");
            container.innerHTML = "Đang tải...";
            let url = `/api/sub-lists/by-lecturer/${lecturerId}`;
            if (courseId !== "all") {
                url += `?course_id=${courseId}`;
            }
            console.log('Fetch URL:', url);
            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Sublist data:', data);
                    container.innerHTML = "";
                    if (!data.sub_list || data.sub_list.length === 0) {
                        container.innerHTML = `<p class="text-muted">Chưa có mã đề nào. Vui lòng tạo mã đề mới.</p>`;
                        return;
                    }
                    data.sub_list.forEach(sublist => {
                        // Lấy course_name từ câu hỏi đầu tiên (nếu có)
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

        // Xử lý filter
        document.getElementById('courseFilter').addEventListener('change', function () {
            fetchListQuestions(this.value);
        });
        document.getElementById('sublistCourseFilter').addEventListener('change', function () {
            fetchSubLists(this.value);
        });

        // Fetch khi tab mã đề được kích hoạt
        document.getElementById('sublist-tab').addEventListener('shown.bs.tab', function () {
            fetchSubLists(document.getElementById('sublistCourseFilter').value);
        });

        // Load ban đầu (chỉ fetch bộ câu hỏi)
        fetchListQuestions();
    });
</script>
