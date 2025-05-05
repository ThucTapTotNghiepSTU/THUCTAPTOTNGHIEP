@section('main-content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f4f8;
        margin: 0;
        padding: 20px;
        min-height: 100vh;
    }

    .exam-container {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 900px;
        margin: auto;
        padding: 30px 40px;
        text-align: center;
        border: 1px solid #d0e2f2;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;
        border-bottom: 2px solid #d0e2f2;
        margin-bottom: 10px;
    }

    .course-info {
        color: #003366;
        font-size: 1.1em;
        font-weight: 500;
        text-align: left;
    }

    .completed {
        color:rgb(61, 185, 12);
        font-weight: bold;
        text-align: right;
    }

    .status_course {
        text-align: right;
        font-size: 1em;
        font-weight: 600;
        color: #0056b3;
    }

    .exam-result {
        margin: 30px 0 20px;
        font-size: 1.4em;
        color: #004085;
        font-weight: bold;
    }

    .exam-result h2 {
        margin-bottom: 8px;
        font-size: 1.6em;
        color: #003366;
    }

    .exam-details {
        text-align: center;
        margin: 25px 0;
    }

    .time-details {
        margin: 15px 0;
        font-size: 1.2em;
    }

    .time-item {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        font-size: 1.2em;
        font-weight: 500;
        color: #003366;
        margin: 8px 0;
    }

    .time-value {
        color: #0066cc;
        font-weight: 600;
    }

    .btn-home {
        display: inline-block;
        margin-top: 30px;
        padding: 12px 36px;
        background-color: #0066cc;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .btn-home:hover {
        background-color: #0050a3;
    }
</style>

<div class="exam-container">
    <div class="header">
        <div class="course-info">
            Lớp của tôi / {{ $courseName }} / <strong>{{ $examTitle }}</strong>
        </div>
        <div class="completed">ĐÃ HOÀN THÀNH</div>
    </div>
    <div class="header">
        <div style="text-align: left;">
            <div>Môn học: <strong>{{ $courseName }}</strong></div>
            <div>Giảng viên: <strong>{{ $teacherName }}</strong></div>
        </div>
        <div class="status_course">{{ $completedExams }}/{{ $totalExams }}</div>
    </div>
    <div class="exam-result">
        <h2>BẠN ĐÃ HOÀN THÀNH BÀI KIỂM TRA</h2>
        <p>Điểm tạm thời: <strong>{{ $examScore }}/10</strong></p>
    </div>
    <div class="exam-details">
        <div class="time-details">
            <div class="time-item">
                ⏳ Thời gian làm bài: <span class="time-value">{{ $examTime }}</span>
            </div>
            <div class="time-item">
                🕒 Thời gian hoàn thành: <span class="time-value">{{ $completionTime }}</span>
            </div>
        </div>
    </div>
    <div class="div_button_start">
        <a href="{{ $homeLink }}" class="btn-home">VỀ TRANG CHỦ</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const examId = {{ $examId }}; // ID bài kiểm tra được truyền từ Controller
        const apiUrl = `/api/exams/${examId}`; // API chi tiết bài kiểm tra

        // Gọi API để lấy dữ liệu bài kiểm tra
        axios.get(apiUrl)
            .then(response => {
                const data = response.data;

                // Cập nhật thông tin bài kiểm tra trên giao diện
                document.querySelector('.course-info').innerHTML = `Lớp của tôi / ${data.courseName} / <strong>${data.examTitle}</strong>`;
                document.querySelector('.completed').innerText = 'ĐÃ HOÀN THÀNH';
                document.querySelector('.status_course').innerText = `${data.completedExams}/${data.totalExams}`;
                document.querySelector('.exam-result').innerHTML = `
                    <h2>BẠN ĐÃ HOÀN THÀNH BÀI KIỂM TRA</h2>
                    <p>Điểm tạm thời: <strong>${data.examScore}/10</strong></p>
                `;
                document.querySelector('.time-details').innerHTML = `
                    <div class="time-item">
                        ⏳ Thời gian làm bài: <span class="time-value">${data.examTime}</span>
                    </div>
                    <div class="time-item">
                        🕒 Thời gian hoàn thành: <span class="time-value">${data.completionTime}</span>
                    </div>
                `;

                // Cập nhật link về trang chủ
                document.querySelector('.btn-home').setAttribute('href', data.homeLink);
            })
            .catch(error => {
                console.error('Error fetching exam details:', error);
                alert('Không thể tải thông tin bài kiểm tra.');
            });
    });
</script>
@endsection
