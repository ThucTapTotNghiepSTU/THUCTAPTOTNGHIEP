@section('main-content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <div id="course-info"></div>
            </div>
            <div class="col-md-6">
                <div id="lecturer-info" class="border p-3 rounded bg-light"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3>Upcoming Exams</h3>
                <div id="exam-container"></div>
            </div>

            <div class="col-md-6">
                <h3>Upcoming Assignments</h3>
                <div id="assignment-container"></div>
            </div>
        </div>

    </div>

    @auth
        <meta name="student_id" content="{{ Auth::user()->student_id }}">
    @endauth

    <script>
        const studentId = document.querySelector('meta[name="student_id"]').getAttribute('content');

        let courseId = null;
        let lecturerId = null;
        let classId = null;

        const storedData = localStorage.getItem("list_id_course_lecturer");
        if (storedData) {
            const listId = JSON.parse(storedData);
            courseId = listId.course_id;
            lecturerId = listId.lecturer_id;
            classId = listId.class_id;
        } else {
            console.log("Không tìm thấy dữ liệu course_id và lecturer_id.");
        }

        document.addEventListener('DOMContentLoaded', function() {
            getCourseInfo(courseId);
            getLecturerInfo(lecturerId);
            getAllStudentTasksOfCourse(studentId, courseId);
        });

        async function getCourseInfo(courseId) {
            const courseInfoDiv = document.getElementById("course-info");

            try {
                const [courseRes, classroom] = await Promise.all([
                    fetch(`/api/courses/getById/${courseId}`).then(res => res.json()),
                    getClassroomInfo(classId)
                ]);

                courseInfoDiv.innerHTML = `
                    <h5>
                        Lớp của tôi /
                        <a href="/myclass" class="text-dark text-decoration-none"><strong>${courseRes.course_name || 'Không có dữ liệu'}</strong></a>
                    </h5>
                    <div class="position-relative rounded overflow-hidden text-white" style="min-height: 250px; background-image: url('images/header_image/default-class.jpg'); background-size: cover; background-position: center;">
                        <div class="position-absolute top-50 start-50 translate-middle bg-dark bg-opacity-50 p-4 rounded">
                            <p class="mb-0"><strong>${classroom?.class_description || 'Không có dữ liệu'}</strong></p>
                        </div>
                    </div>
                `;
            } catch (err) {
                console.error(err);
                courseInfoDiv.innerHTML = '<p class="text-danger">Không thể tải thông tin môn học.</p>';
            }
        }

        function getLecturerInfo(lecturerId) {
            const lecturerInfoDiv = document.getElementById("lecturer-info");

            fetch(`/api/lecturers/getById/${lecturerId}`)
                .then(res => res.json())
                .then(lecturer => {
                    lecturerInfoDiv.innerHTML = `
                        <p><strong>Tên giáo viên:</strong> ${lecturer.fullname || 'Không có dữ liệu'}</p>
                        <p><strong>Email:</strong> ${lecturer.school_email || 'Không có dữ liệu'}</p>
                        <p><strong>Email cá nhân:</strong> ${lecturer.personal_email || 'Không có dữ liệu'}</p>
                        <p><strong>Số điện thoại:</strong> ${lecturer.phone || 'Không có dữ liệu'}</p>
                    `;
                })
                .catch(err => {
                    console.error(err);
                    lecturerInfoDiv.innerHTML = '<p class="text-danger">Không thể tải thông tin giảng viên.</p>';
                });
        }

        function getClassroomInfo(classId) {
            return fetch(`/api/classrooms/getById/${classId}`)
                .then(res => res.json())
                .then(classroom => {
                    return classroom;
                })
                .catch(err => {
                    console.error(err);
                    return null;
                });
        }

        async function getAllStudentTasksOfCourse(studentId, courseId) {
            try {
                const res = await fetch(`/api/getAllStudentTasksOfCourse/${studentId}/${courseId}`);
                const data = await res.json();
                const examContainer = document.getElementById('exam-container');
                const assignmentContainer = document.getElementById('assignment-container');

                examContainer.innerHTML = '';
                assignmentContainer.innerHTML = '';

                data.exams.forEach(exam => {
                    const isPending = exam.status === 'Pending';
                    const badgeColor = isPending ? 'warning text-dark' : 'success';
                    const hasScore = exam.temporary_score != null; // Kiểm tra xem bài đã có điểm chưa

                    const now = new Date();
                    const startTime = new Date(exam.start_time);
                    const endTime = new Date(exam.end_time);
                    const showButton = isPending && now >= startTime && now <= endTime;

                    examContainer.innerHTML += `
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="mb-1"><i class="bi bi-journal-check me-2 text-primary"></i>${exam.title}</h5>
                                <p class="text-muted mb-2">${exam.content}</p>
                            </div>
                            <span class="badge rounded-pill bg-${badgeColor}">${exam.status}</span>
                        </div>
                        <ul class="list-unstyled mb-3">
                            <li><i class="bi bi-bookmark me-2"></i><strong>Loại:</strong> ${exam.type}</li>
                            <li><i class="bi bi-clock me-2"></i><strong>Bắt đầu:</strong> ${exam.start_time}</li>
                            <li><i class="bi bi-clock-history me-2"></i><strong>Kết thúc:</strong> ${exam.end_time}</li>
                        </ul>
                        ${showButton ? `
                                        <a href="/task/start?id=${exam.exam_id}" class="btn btn-primary w-100 mt-2">
                                            <i class="bi bi-pencil-square me-1"></i> Làm bài ngay
                                        </a>` : ''}
                        ${hasScore ? `
                                        <div class="mt-3"><strong>Điểm:</strong> ${exam.temporary_score}</div>` : ''}
                    </div>
                </div>
            `;
                });

                data.assignments.forEach(assign => {
                    const isPending = assign.status === 'Pending';
                    const badgeColor = isPending ? 'warning text-dark' : 'success';
                    const hasScore = assign.temporary_score != null; // Kiểm tra xem bài đã có điểm chưa

                    const now = new Date();
                    const startTime = new Date(assign.start_time);
                    const endTime = new Date(assign.end_time);
                    const showButton = isPending && now >= startTime && now <= endTime;

                    assignmentContainer.innerHTML += `
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="mb-1"><i class="bi bi-clipboard-data me-2 text-info"></i>${assign.title}</h5>
                                <p class="text-muted mb-2">${assign.content}</p>
                            </div>
                            <span class="badge rounded-pill bg-${badgeColor}">${assign.status}</span>
                        </div>
                        <ul class="list-unstyled mb-3">
                            <li><i class="bi bi-bookmark me-2"></i><strong>Loại:</strong> ${assign.type}</li>
                            <li><i class="bi bi-clock me-2"></i><strong>Bắt đầu:</strong> ${assign.start_time}</li>
                            <li><i class="bi bi-clock-history me-2"></i><strong>Kết thúc:</strong> ${assign.end_time}</li>
                        </ul>
                        ${showButton ? `
                                        <a href="/task/start?id=${assign.assignment_id}" class="btn btn-info text-white w-100 mt-2">
                                            <i class="bi bi-pencil-square me-1"></i> Làm bài ngay
                                        </a>` : ''}
                        ${hasScore ? `
                                        <div class="mt-3"><strong>Điểm:</strong> ${assign.temporary_score}</div>` : ''}
                    </div>
                </div>
            `;
                });
            } catch (err) {
                console.error("Lỗi khi tải bài kiểm tra và bài tập:", err);
            }
        }
    </script>
@endsection
