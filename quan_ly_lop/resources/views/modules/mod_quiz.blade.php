@section('main-content')
    <div class="container mt-5">
        <!-- Loading indicator -->
        <div id="loading" class="text-center">Đang tải...</div>

        <div id="quiz-container" class="card shadow-lg rounded d-none">
            <div class="card-body">
                <h3 id="quiz-title" class="card-title text-center mb-4"></h3>

                <form id="quiz-form">
                    @csrf

                    <div id="quiz-questions"></div>

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
            const id = urlParams.get('id'); // Lấy tham số id từ URL
            const studentId = document.querySelector('meta[name="student_id"]').getAttribute('content');

            const loadingEl = document.getElementById('loading');
            const quizContainer = document.getElementById('quiz-container');
            const errorMsg = document.getElementById('error-msg');
            const successMsg = document.getElementById('success-msg');
            const questionContainer = document.getElementById('quiz-questions');
            const quizTitle = document.getElementById('quiz-title');

            // Kiểm tra xem ID có tồn tại không
            if (!id) {
                errorMsg.classList.remove('d-none');
                errorMsg.innerText = "Không tìm thấy ID bài thi hoặc bài tập!";
                return;
            }

            try {
                loadingEl.classList.remove('d-none');

                let res, data;

                // Kiểm tra xem ID là của bài thi hay bài tập
                res = await fetch(`/api/exams/getById/${id}`); // Kiểm tra bài thi trước
                if (res.ok) {
                    data = await res.json(); // Chỉ chuyển thành JSON nếu phản hồi là OK
                }

                // Nếu không phải bài thi, kiểm tra xem đó có phải bài tập không
                if (!data) {
                    res = await fetch(
                        `/api/assignments/getById/${id}`); // Nếu không phải bài thi thì lấy bài tập
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

                quizTitle.textContent = data.title;
                quizContainer.classList.remove('d-none');
                const isSimultaneous = data.isSimultaneous === true || data.isSimultaneous === 1;

                const startTimeStr = data.start_time;
                let endTimeStr = data.end_time;
                console.log(startTimeStr,endTimeStr);
                // Chuyển đổi thành chuỗi ISO 8601 (thay dấu cách thành T)
                const startTimeISO = startTimeStr.replace(' ', 'T');
                endTimeStr = endTimeStr.replace(' ', 'T');

                // Tạo đối tượng Date từ chuỗi ISO
                const startTime = new Date(startTimeISO).getTime();
                let endTime = new Date(endTimeStr).getTime();
                const now = new Date().getTime(); // Thời gian hiện tại

                // Nếu không phải bài thi cùng thời gian, cộng thêm 30 ngày
                if (!isSimultaneous) {
                    endTime += 30 * 24 * 60 * 60 * 1000; // Thêm 30 ngày
}

                // Kiểm tra và in ra thời gian để debug
                console.log('Start time (timestamp):', startTime);
                console.log('End time (timestamp):', endTime);
                console.log('Current time (timestamp):', now);

                // Tính thời gian còn lại (nếu thời gian đã qua, không cho phép nộp bài nữa)
                let remainingTime = Math.floor((endTime - now) / 1000); // tính bằng giây

                if (remainingTime < 0) {
                    remainingTime = 0; // Nếu hết thời gian, set lại còn 0
                }

                // In ra thời gian còn lại
                const minutes = Math.floor(remainingTime / 60);
                const seconds = remainingTime % 60;
                console.log(`Thời gian còn lại: ${minutes} phút ${seconds < 10 ? '0' : ''}${seconds} giây`);


                // Lưu endTime vào localStorage để giữ khi reload
                localStorage.setItem(`quiz_${id}_end_time`, endTime);

                // Nếu còn thời gian thì bắt đầu đếm ngược
                if (remainingTime > 0 && isSimultaneous) {
                    const timerEl = document.createElement('div');
                    timerEl.className = 'alert alert-info text-center';
                    timerEl.id = 'countdown-timer';
                    quizContainer.prepend(timerEl);

                    async function submitQuizAutomatically() {
                        const answers = {};

                        questions.forEach(q => {
                            const selected = document.querySelector(
                                `input[name="answers[${q.question_id}]"]:checked`);
                            answers[q.question_id] = {
                                question_id: q.question_id,
                                answer_content: selected ? selected.value : "Chưa trả lời"
                            };
                        });

                        const response = await fetch('/api/student/submit-answers', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                student_id: studentId,
                                exam_id: data.exam_id || null,
                                assignment_id: data.assignment_id || null,
                                answers
                            })
                        });

                        if (response.ok) {
                            const result = await response.json();
                            successMsg.classList.remove('d-none');
                            successMsg.innerText = result.message || "Tự động nộp bài thành công!";
                            quizContainer.classList.add('d-none');
                            localStorage.removeItem(`quiz_${id}_end_time`);
                            setTimeout(() => window.location.href = '/classDetail', 2000);
                        } else {
                            errorMsg.classList.remove('d-none');
                            errorMsg.innerText = "Không thể tự động nộp bài. Vui lòng thử lại!";
                        }
                    }

                    const updateTimer = () => {
                        const now = new Date().getTime();
                        const storedEndTime = parseInt(localStorage.getItem(`quiz_${id}_end_time`));
                        const secondsLeft = Math.floor((storedEndTime - now) / 1000);

                        if (secondsLeft <= 0) {
                            timerEl.innerText = 'Hết thời gian! Đang nộp bài...';
                            submitQuizAutomatically(); // gọi hàm nộp bài tự động
                            clearInterval(countdown);
                            return;
                        }

                        const minutes = Math.floor(secondsLeft / 60);
                        const seconds = secondsLeft % 60;
                        timerEl.innerText =
                            `Thời gian còn lại: ${minutes} phút ${seconds < 10 ? '0' : ''}${seconds} giây`;
                    };

                    updateTimer(); // Cập nhật ngay lần đầu
                    const countdown = setInterval(updateTimer, 1000);
                }

                const questions = data.questions || [];

                questions.forEach((q, index) => {
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('mb-4');

                    const questionHtml = `
                        <p><strong>Câu ${index + 1}:</strong> ${q.content}</p>
                        ${q.choices.map((choice, i) => `
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[${q.question_id}]" value="${choice}" id="q${q.question_id}_${i}">
                                <label class="form-check-label" for="q${q.question_id}_${i}">
                                    ${choice}
                                </label>
                            </div>
                        `).join('')}
                    `;

                    wrapper.innerHTML = questionHtml;
                    questionContainer.appendChild(wrapper);
                });

                // Xử lý submit bài
                document.getElementById('quiz-form').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const formData = new FormData(e.target);
                    let hasUnanswered = false;
                    const answers = {};

                    // Thu thập dữ liệu câu trả lời
                    questions.forEach(q => {
                        const selected = formData.get(
                            `answers[${q.question_id}]`); // Lấy câu trả lời từ formData

                        if (!selected) {
                            hasUnanswered = true;
                            alert(`Câu hỏi "${q.content}" chưa được trả lời.`);
                        } else {
                            // Lưu dữ liệu trả lời vào answers
                            answers[q.question_id] = {
                                question_id: q.question_id, // ID câu hỏi
                                answer_content: selected // Nội dung câu trả lời
                            };
                        }
                    });

                    if (hasUnanswered) return;

                    // Gửi yêu cầu nộp bài
                    const response = await fetch('/api/student/submit-answers', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            student_id: studentId,
                            exam_id: data.exam_id || null,
                            assignment_id: data.assignment_id || null,
                            answers
                        })
                    });

                    const responseText = await response.text();
                    console.log('Response Text:', responseText);

                    if (response.ok) {
                        try {
                            const result = JSON.parse(responseText);
                            successMsg.classList.remove('d-none');
                            successMsg.innerText = result.message || "Nộp bài thành công!";
                            quizContainer.classList.add('d-none');
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
