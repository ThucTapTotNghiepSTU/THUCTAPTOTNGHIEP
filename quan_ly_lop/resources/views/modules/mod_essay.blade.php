@section('main-content')
    <div class="container mt-5">
        <!-- Loading indicator -->
        <div id="loading" class="text-center">Đang tải...</div>

        <div id="assay-container" class="card shadow-lg rounded d-none">
            <div class="card-body">
                <h3 id="assay-title" class="card-title text-center mb-4"></h3>

                <form id="assay-form">
                    @csrf
                    <div id="assay-questions"></div>

                    <div class="mb-3">
                        <label for="file-upload" class="form-label">Tải lên file (nếu có)</label>
                        <input type="file" class="form-control" id="answer_file" name="answer_file">
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success">Nộp bài</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="error-msg" class="alert alert-danger d-none mt-3"></div>
        <div id="success-msg" class="alert alert-success d-none mt-3"></div>
    </div>

    @auth
        <meta name="student_id" content="{{ Auth::user()->student_id }}">
    @endauth
    <style>
        #countdown-timer {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            z-index: 1000;
            /* Đảm bảo đếm ngược luôn hiển thị trên các phần tử khác */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');
            const studentId = document.querySelector('meta[name="student_id"]').getAttribute('content');
            const loadingEl = document.getElementById('loading');
            const assayContainer = document.getElementById('assay-container');
            const errorMsg = document.getElementById('error-msg');
            const successMsg = document.getElementById('success-msg');
            const questionContainer = document.getElementById('assay-questions');
            const assayTitle = document.getElementById('assay-title');

            // Kiểm tra ID
            if (!id) {
                errorMsg.classList.remove('d-none');
                errorMsg.innerText = "Không tìm thấy ID bài thi hoặc bài tập!";
                return;
            }

            try {
                loadingEl.classList.remove('d-none');
                let res, data;

                // Kiểm tra bài thi
                res = await fetch(`/api/exams/getById/${id}`);
                if (res.ok) {
                    data = await res.json();
                }

                // Kiểm tra bài tập
                if (!data) {
                    res = await fetch(`/api/assignments/getById/${id}`);
                    if (res.ok) {
                        data = await res.json();
                    }
                }

                loadingEl.classList.add('d-none');
                if (!data) {
                    errorMsg.classList.remove('d-none');
                    errorMsg.innerText = "Không tìm thấy bài thi hoặc bài tập!";
                    return;
                }

                assayTitle.textContent = data.title;
                assayContainer.classList.remove('d-none');

                const startTimeStr = data.start_time;
                let endTimeStr = data.end_time;
                console.log(startTimeStr, endTimeStr);
                const startTimeISO = startTimeStr.replace(' ', 'T');
                endTimeStr = endTimeStr.replace(' ', 'T');


                const startTime = new Date(startTimeISO).getTime();
                let endTime = new Date(endTimeStr).getTime();
                const now = new Date().getTime();

                const isSimultaneous = data.isSimultaneous === true || data.isSimultaneous === 1;

                if (!isSimultaneous) {
                    endTime += 30 * 24 * 60 * 60 * 1000; // Thêm 30 ngày nếu không phải bài thi cùng thời gian
                }

                let remainingTime = Math.floor((endTime - now) / 1000);
                if (remainingTime < 0) {
                    remainingTime = 0;
                }

                const minutes = Math.floor(remainingTime / 60);
                const seconds = remainingTime % 60;

                // Lưu endTime vào localStorage với khóa duy nhất
                const endTimeKey = `assay_${id}_end_time`;
                localStorage.setItem(endTimeKey, endTime);

                if (remainingTime > 0 && isSimultaneous) {
                    const timerEl = document.createElement('div');
                    timerEl.className = 'alert alert-info text-center';
                    timerEl.id = 'countdown-timer';
                    assayContainer.prepend(timerEl);

                    // Hàm tự động nộp bài
                    async function submitAssayAutomatically() {
                        const answers = {};
                        let hasUnanswered = false;

                        questions.forEach(q => {
                            const textarea = document.querySelector(
                                `textarea[name="${q.question_id}"]`);
                            const answer = textarea ? textarea.value.trim() : "";
                            if (!answer) {
                                hasUnanswered = true;
                            }
                            answers[q.question_id] = {
                                question_id: q.question_id,
                                answer_content: answer || "Chưa trả lời"
                            };
                        });

                        //xoá đáp án tạm
                    try {


                        const payload = {
                            student_id: studentId
                        };
                        if (data.exam_id) payload.exam_id = data.exam_id;
                        if (data.assignment_id) payload.assignment_id = data.assignment_id;

                        await fetch(`/answers/temp/delete`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        console.log("Đã xóa đáp án tạm thời trước khi nộp bài.");
                    } catch (err) {
                        console.warn("Không thể xóa đáp án tạm:", err);
                    }

                        const formData = new FormData();
                        formData.append('student_id', studentId);
                        formData.append('exam_id', data.exam_id || '');
                        formData.append('assignment_id', data.assignment_id || '');
                        formData.append('answers', JSON.stringify(Object.values(answers)));

                        const fileInput = document.getElementById('answer_file');
                        if (fileInput && fileInput.files.length > 0) {
                            formData.append('answer_file', fileInput.files[0]);
                        }

                        try {
                            const response = await fetch('/api/student/submit-answers', {
                                method: 'POST',
                                body: formData
                            });

                            const resultText = await response.text();
                            if (response.ok) {
                                try {
                                    const result = JSON.parse(resultText);
                                    successMsg.classList.remove('d-none');
                                    successMsg.innerText = result.message || "Tự động nộp bài thành công!";
                                    assayContainer.classList.add('d-none');
                                    localStorage.removeItem(endTimeKey);
                                    setTimeout(() => window.location.href = '/classDetail', 2000);
                                } catch (e) {
                                    errorMsg.classList.remove('d-none');
                                    errorMsg.innerText = "Lỗi xử lý phản hồi từ server.";
                                }
                            } else {
                                errorMsg.classList.remove('d-none');
                                errorMsg.innerText = resultText ||
                                    "Không thể tự động nộp bài. Vui lòng thử lại!";
                            }
                        } catch (error) {
                            errorMsg.classList.remove('d-none');
                            errorMsg.innerText = "Đã xảy ra lỗi khi tự động nộp bài.";
                            console.error(error);
                        }
                    }

                    // Cập nhật thời gian đếm ngược
                    const updateTimer = () => {
                        const now = new Date().getTime();
                        const storedEndTime = parseInt(localStorage.getItem(endTimeKey));
                        const secondsLeft = Math.floor((storedEndTime - now) / 1000);

                        if (secondsLeft <= 0) {
                            timerEl.innerText = 'Hết thời gian! Đang nộp bài...';
                            clearInterval(countdown);
                            submitAssayAutomatically();
                            return;
                        }

                        const minutes = Math.floor(secondsLeft / 60);
                        const seconds = secondsLeft % 60;
                        timerEl.innerText =
                            `Thời gian còn lại: ${minutes} phút ${seconds < 10 ? '0' : ''}${seconds} giây`;
                    };


                    updateTimer();
                    const countdown = setInterval(updateTimer, 1000);
                }

                const questions = data.questions || [];


                let savedAnswers = {};

                try {
                    const dataID = data.exam_id || data.assignment_id;
                    const tempUrl = `/api/student/temp-answer/${dataID}/${studentId}`;

                    const tempRes = await fetch(tempUrl);
                    if (tempRes.ok) {
                        const tempData = await tempRes.json();

                        if (tempData.length > 0) {
                            tempData.forEach(a => {
                                savedAnswers[a.question_id] = a.answer;
                            });
                            console.log("Tìm thấy đáp án tạm thời.");
                            console.log("savedAnswers:", savedAnswers);
                        } else {
                            console.warn("Không tìm thấy đáp án tạm thời.");
                        }
                    } else {
                        console.warn("Không thể tải đáp án tạm thời: Lỗi từ server.");
                    }
                } catch (err) {
                    console.warn("Không thể tải đáp án tạm thời:", err);
                }

                // Danh sách lưu tạm đáp án chọn
                const answers = [];

                questions.forEach((q, index) => {
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('mb-4');

                    const textareaId = `answer_${q.question_id}`;
                    const questionHtml = `
                        <p><strong>Câu ${index + 1}:</strong> ${q.content}</p>
                        <textarea name="${q.question_id}" id="${textareaId}" class="form-control" rows="4" placeholder="Nhập câu trả lời...">${savedAnswers[q.question_id] || ''}</textarea>
                    `;

                    wrapper.innerHTML = questionHtml;
                    questionContainer.appendChild(wrapper);

                    const textarea = wrapper.querySelector('textarea');

                    let typingTimeout;

                    textarea.addEventListener('input', () => {
                        clearTimeout(typingTimeout);
                        typingTimeout = setTimeout(() => {
                            const answer = textarea.value;
                            const questionId = textarea.name;

                            const existingIndex = answers.findIndex(a => a
                                .question_id ===
                                questionId);

                            if (existingIndex !== -1) {
                                answers[existingIndex].answer = answer;
                            } else {
                                answers.push({
                                    question_id: questionId,
                                    answer: answer
                                });
                            }

                            const payload = {
                                student_id: studentId,
                                answers: answers
                            };

                            payload.exam_id = data.exam_id || null;
                            payload.assignment_id = data.assignment_id || null;


                            fetch('/api/student/answers/temp', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify(payload)
                                })
                                .then(response => response.json())
                                .then(() => {
                                    console.log('Đã lưu tạm các đáp án:', answers);
                                })
                                .catch(error => {
                                    console.error('Lỗi khi lưu tạm đáp án:', error);
                                });
                        }, 1000);
                    });
                });


                document.getElementById('assay-form').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const formData = new FormData(e.target);
                    let hasUnanswered = false;
                    const answers = {};

                    questions.forEach(q => {
                        const answer = formData.get(`${q.question_id}`);
                        if (!answer || !answer.trim()) {
                            hasUnanswered = true;
                            alert(`Câu hỏi "${q.content}" chưa được trả lời.`);
                        } else {
                            answers[q.question_id] = {
                                question_id: q.question_id,
                                answer_content: answer.trim()
                            };
                        }
                    });

                    if (hasUnanswered) return;

                    //xoá đáp án tạm
                    try {


                        const payload = {
                            student_id: studentId
                        };
                        if (data.exam_id) payload.exam_id = data.exam_id;
                        if (data.assignment_id) payload.assignment_id = data.assignment_id;

                        await fetch(`/answers/temp/delete`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        console.log("Đã xóa đáp án tạm thời trước khi nộp bài.");
                    } catch (err) {
                        console.warn("Không thể xóa đáp án tạm:", err);
                    }

                    if (!data.exam_id && !data.assignment_id) {
                        errorMsg.classList.remove('d-none');
                        errorMsg.innerText = "Bạn phải chọn hoăc bài thi hoặc bài tập!";
                        return;
                    }

                    formData.append('student_id', studentId);
                    formData.append('exam_id', data.exam_id || '');
                    formData.append('assignment_id', data.assignment_id || '');
                    formData.append('answers', JSON.stringify(Object.values(answers)));

                    const fileInput = document.getElementById('answer_file');
                    if (fileInput && fileInput.files.length > 0) {
                        formData.append('answer_file', fileInput.files[0]);
                    }

                    const response = await fetch('/api/student/submit-answers', {
                        method: 'POST',
                        body: formData
                    });

                    const responseText = await response.text();
                    if (response.ok) {
                        try {
                            const result = JSON.parse(responseText);
                            successMsg.classList.remove('d-none');
                            successMsg.innerText = result.message || "Nộp bài thành công!";
                            assayContainer.classList.add('d-none');
                            window.location.href = '/classDetail';
                        } catch (e) {
                            errorMsg.classList.remove('d-none');
                            errorMsg.innerText = "Lỗi xử lý phản hồi từ server.";
                        }
                    } else {
                        errorMsg.classList.remove('d-none');
                        errorMsg.innerText = responseText || "Đã xảy ra lỗi khi nộp bài.";
                    }
                });
            } catch (err) {
                console.error(err);
                loadingEl.classList.add('d-none');
                errorMsg.classList.remove('d-none');
                errorMsg.innerText = "Không thể tải dữ liệu bài thi hoặc bài tập.";
            }
        });
    </script>
@endsection
