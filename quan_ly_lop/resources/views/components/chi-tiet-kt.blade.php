@extends('layouts.app')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
        min-height: 100vh;
    }
    .exam-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 900px;
        margin: auto;
        padding: 20px;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 10px;
        border-bottom: 2px solid #ddd;
    }
    .course-info {
        color: #333;
        font-size: 1em;
        font-weight: bold;
    }
    .not-complete {
        color: #e9182d;
        font-weight: bold;
        text-align: right;
    }
    .status_course {
        text-align: right;
        font-size: 1em;
        font-weight: bold;
    }
    .exam-time-info {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #e9ecef;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
    }
    .time-arrow {
        margin: 0 10px;
        font-size: 20px;
    }
    .exam-content {
        display: flex;
        gap: 20px;
    }
    .exam-instructions {
        background-color: #e9ecef;
        padding: 15px;
        border-radius: 8px;
        flex: 2;
    }
    .exam-attachments {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        flex: 1;
        text-align: center;
        border: 1px dashed #ccc;
    }
    .exam-attachments button {
        display: block;
        width: 100%;
        margin: 5px 0;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-align: left;
    }
    .div_button_start {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .start-exam {
        padding: 12px 40px;
        background-color: #292c29;
        color: white;
        border: none;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
    }
</style>

<div class="exam-container">
    <div class="header">
        <div class="course-info">Lớp của tôi / Đang tải...</div>
        <div class="not-complete">Đang tải...</div>
    </div>
    <div class="header">
        <div>
            <div>Môn học: <strong class="course-name">Đang tải...</strong></div>
            <div>Giảng viên: <strong class="teacher-name">Đang tải...</strong></div>
        </div>
        <div class="status_course">Đang tải...</div>
    </div>
    <div class="exam-time-info">
        <div><strong>Start:</strong> Đang tải...</div>
        <div class="time-arrow">→</div>
        <div><strong>End:</strong> Đang tải...</div>
    </div>
    <h2>Thi học kỳ môn <span class="exam-title">Đang tải...</span></h2>
    <div class="exam-content">
        <div class="exam-instructions">
            <h3>Thông tin bài thi:</h3>
            <p>Hình thức: Đang tải...</p>
            <p>Thời gian: Đang tải...</p>
            <p>Ghi chú: Đang tải...</p>
        </div>
        <div class="exam-attachments">
            <h3>📎 TÀI LIỆU ÔN THI</h3>
            <p>Đang tải...</p>
        </div>
    </div>
    <div class="div_button_start">
        <a href="#" class="start-exam">START</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const examId = {{ $examId }}; // ID bài kiểm tra được truyền từ Controller
        const apiUrl = `/api/exams/${examId}`; // Sử dụng API chi tiết bài kiểm tra

        // Gọi API để lấy dữ liệu bài kiểm tra
        axios.get(apiUrl)
            .then(response => {
                const data = response.data;

                // Cập nhật thông tin bài kiểm tra trên giao diện
                document.querySelector('.course-info').innerHTML = `Lớp của tôi / ${data.courseName} / <strong>${data.examTitle}</strong>`;
                document.querySelector('.not-complete').innerText = data.examStatus;
                document.querySelector('.course-name').innerText = data.courseName;
                document.querySelector('.teacher-name').innerText = data.teacherName;
                document.querySelector('.status_course').innerText = `${data.completedExams}/${data.totalExams}`;
                document.querySelector('.exam-time-info').innerHTML = `
                    <div><strong>Start:</strong> ${data.startTime}</div>
                    <div class="time-arrow">→</div>
                    <div><strong>End:</strong> ${data.endTime}</div>
                `;
                document.querySelector('.exam-title').innerText = data.examTitle;
                document.querySelector('.exam-instructions').innerHTML = `
                    <h3>Thông tin bài thi:</h3>
                    <p>Hình thức: ${data.examType}</p>
                    <p>Thời gian: ${data.examDuration} phút</p>
                    <p>${data.examNotes}</p>
                `;

                // Hiển thị tài liệu ôn thi (nếu có)
                const materialsContainer = document.querySelector('.exam-attachments');
                if (data.examMaterials && data.examMaterials.length > 0) {
                    materialsContainer.innerHTML = '<h3>📎 TÀI LIỆU ÔN THI</h3>';
                    data.examMaterials.forEach(material => {
                        const materialItem = document.createElement('button');
                        materialItem.innerText = material;
                        materialsContainer.appendChild(materialItem);
                    });
                } else {
                    materialsContainer.innerHTML = '<p>Không có tài liệu ôn thi.</p>';
                }

                // Cập nhật link bắt đầu thi
                document.querySelector('.start-exam').setAttribute('href', data.examLink);
            })
            .catch(error => {
                console.error('Error fetching exam details:', error);
                alert('Không thể tải thông tin bài kiểm tra.');
            });
    });
</script>
@endsection
