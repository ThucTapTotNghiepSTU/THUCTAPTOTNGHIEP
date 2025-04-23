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
        @auth('lecturer')
            <!-- Nút mở modal -->
            <div class="d-flex justify-content-start mb-3 mt-3 gap-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createExamModal">
                    <i class="bi bi-plus-circle me-1"></i> Thêm bài kiểm tra
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAssignmentModal">
                    <i class="bi bi-plus-circle me-1"></i> Thêm bài tập
                </button>
            </div>
        @endauth
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

        @auth('lecturer')
            <<!-- Modal tạo bài kiểm tra -->
                <div class="modal fade modal-xl" id="createExamModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form id="examForm" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tạo bài kiểm tra</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @include('modules/mod_lecturer.partials.exam-form-fields')
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Tạo bài</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal tạo bài tập -->
                <div class="modal fade modal-xl" id="createAssignmentModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form id="assignmentForm" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tạo bài tập</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @include('modules/mod_lecturer.partials.assignment-form-fields')
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Tạo bài</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Thẻ meta để xác thực -->
                @auth
                    <meta name="student_id" content="{{ Auth::user()->student_id }}">
                    <meta name="lecturer_id" content="{{ Auth::user()->lecturer_id }}">
                @endauth
                <!-- Script dành cho giảng viên -->
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const lecturerId = '{{ Auth::user()->lecturer_id }}';
                        const token = localStorage.getItem('token');
                        let subListData = [];
                        let courseId = null;
                        let classId = null;

                        // Lấy courseId và classId từ localStorage
                        const storedData = localStorage.getItem("list_id_course_lecturer");
                        if (storedData) {
                            try {
                                const listId = JSON.parse(storedData);
                                courseId = listId.course_id;
                                classId = listId.class_id;
                                console.log('localStorage data:', listId);
                            } catch (e) {
                                console.error("Lỗi khi parse dữ liệu từ localStorage:", e);
                            }
                        }

                        // Nếu courseId không có, thử lấy từ API
                        if (!courseId && classId) {
                            fetch(`/api/classrooms/getById/${classId}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Authorization': `Bearer ${token}`
                                }
                            })
                                .then(res => {
                                    if (!res.ok) {
                                        throw new Error(`HTTP error! status: ${res.status}`);
                                    }
                                    return res.json();
                                })
                                .then(classroom => {
                                    if (classroom && classroom.course_id) {
                                        courseId = classroom.course_id;
                                        console.log('courseId from API:', courseId);
                                        if (classroom.lecturer_id !== lecturerId) {
                                            console.warn('Giảng viên không có quyền truy cập lớp học này');
                                            courseId = null;
                                        }
                                    } else {
                                        console.warn('Không thể lấy course_id từ API');
                                    }
                                })
                                .catch(err => {
                                    console.error('Lỗi khi lấy thông tin lớp học:', err);
                                })
                                .finally(() => {
                                    initializePage();
                                });
                        } else {
                            // Fallback: Lấy từ URL
                            const urlParams = new URLSearchParams(window.location.search);
                            courseId = courseId || urlParams.get('course_id');
                            classId = classId || urlParams.get('class_id');
                            initializePage();
                        }

                        // Hàm khởi tạo trang


                        function initializePage() {
                            if (!courseId || !classId) {
                                document.getElementById("course-info").innerHTML = '<p class="text-danger">Không thể tải thông tin môn học do thiếu dữ liệu lớp học.</p>';
                                document.getElementById("exam-container").innerHTML = '<p class="text-danger">Không thể tải danh sách bài kiểm tra.</p>';
                                document.getElementById("assignment-container").innerHTML = '<p class="text-danger">Không thể tải danh sách bài tập.</p>';
                                return;
                            }

                            getAllTasksOfCourse(courseId);
                            getCourseInfo(courseId, classId);
                        }

                        // Lấy danh sách mã đề từ API
                        if (lecturerId) {
                            fetch(`/api/sub-lists/by-lecturer/${lecturerId}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Authorization': `Bearer ${token}`
                                }
                            })
                                .then(res => res.json())
                                .then(data => {
                                    subListData = data.sub_list || [];
                                })
                                .catch(err => console.error('Lỗi khi lấy danh sách mã đề:', err));
                        }

                        // Hàm cập nhật danh sách mã đề dựa trên kiểu đề
                        function updateSubListOptions(examType, subListSelect) {
                            subListSelect.innerHTML = '<option value="">Chọn mã đề</option>';
                            const filteredSubLists = subListData.filter(sublist => {
                                return sublist.questions.every(q => q.type === examType);
                            });
                            filteredSubLists.forEach(sublist => {
                                const option = document.createElement('option');
                                option.value = sublist.sub_list_id;
                                option.text = sublist.title;
                                subListSelect.appendChild(option);
                            });
                        }

                        // Xử lý sự kiện thay đổi kiểu đề
                        document.querySelectorAll('#exam_type').forEach(select => {
                            select.addEventListener('change', (e) => {
                                const examType = e.target.value;
                                const subListSelect = e.target.closest('form').querySelector('#sub_list_id');
                                if (examType) {
                                    updateSubListOptions(examType, subListSelect);
                                } else {
                                    subListSelect.innerHTML = '<option value="">Chọn mã đề</option>';
                                }
                                e.target.closest('form').querySelector('#type').value = examType;
                            });
                        });

                        // Hàm tính toán status dựa trên thời gian
                        function calculateStatus(startTime, endTime) {
                            const now = new Date();
                            const start = new Date(startTime);
                            const end = new Date(endTime);
                            if (now < start) return 'Pending';
                            if (now >= start && now <= end) return 'Processing';
                            if (now > end) return 'Completed';
                            return 'Pending';
                        }

                        // Submit form tạo bài kiểm tra
                        document.getElementById('examForm').addEventListener('submit', async (e) => {
                            e.preventDefault();

                            const form = e.target;

                            // ✅ Hiển thị loading đơn giản (nếu cần)
                            const submitBtn = form.querySelector('button[type="submit"]');
                            submitBtn.disabled = true;
                            submitBtn.textContent = 'Đang tạo...';

                            // ✅ Lấy và chuẩn hóa dữ liệu
                            const startTime = new Date(form.start_time.value).toISOString().slice(0, 19).replace('T', ' ');
                            const endTime = new Date(form.end_time.value).toISOString().slice(0, 19).replace('T', ' ');
                            const status = calculateStatus(startTime, endTime);

                            const data = {
                                sub_list_id: form.sub_list_id.value,
                                title: form.title.value,
                                content: form.content.value,
                                type: form.type.value,
                                isSimultaneous: form.isSimultaneous.checked ? 1 : 0,
                                start_time: startTime,
                                end_time: endTime,
                                status: status
                            };

                            try {
                                const res = await fetch('/api/exams/create', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Authorization': `Bearer ${token}`
                                    },
                                    body: JSON.stringify(data)
                                });

                                if (!res.ok) {
                                    const errorData = await res.json();
                                    throw new Error(`HTTP error! status: ${res.status}, message: ${errorData.message}`);
                                }
                                await res.json();
                                alert('✅ Tạo bài kiểm tra thành công!');
                                form.reset();
                                const modal = bootstrap.Modal.getInstance(document.getElementById('createExamModal'));
                                modal.hide();
                            } catch (err) {
                                console.error('Lỗi tạo bài kiểm tra:', err);
                                alert(`❌ Có lỗi xảy ra khi tạo bài kiểm tra: ${err.message}`);
                            } finally {
                                submitBtn.disabled = false;
                                submitBtn.textContent = 'Tạo';
                            }
                        });


                        // Submit form tạo bài tập
                        document.getElementById('assignmentForm').addEventListener('submit', async (e) => {
                            e.preventDefault();
                            const form = e.target;
                            const startTime = new Date(form.start_time.value).toISOString().slice(0, 16);
                            const endTime = new Date(form.end_time.value).toISOString().slice(0, 16);
                            const status = calculateStatus(startTime, endTime);
                            const data = {
                                sub_list_id: form.sub_list_id.value,
                                title: form.title.value,
                                content: form.content.value,
                                type: form.type.value,
                                isSimultaneous: form.isSimultaneous.checked ? 1 : 0,
                                show_result: form.show_result.checked ? 1 : 0,
                                start_time: startTime,
                                end_time: endTime,
                                status: status,
                            };
                            console.log(data);
                            try {
                                const res = await fetch('/api/assignments/create', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Authorization': `Bearer ${token}`
                                    },
                                    body: JSON.stringify(data)
                                });
                                if (!res.ok) {
                                    const errorData = await res.json();
                                    throw new Error(`HTTP error! status: ${res.status}, message: ${errorData.message}`);
                                }
                                const result = await res.json();
                                alert('✅ Tạo bài tập thành công!');
                                form.reset();
                                const modal = bootstrap.Modal.getInstance(document.getElementById('createAssignmentModal'));
                                modal.hide();
                                location.reload();
                            } catch (err) {
                                console.error('Lỗi tạo bài tập:', err);
                                alert(`❌ Có lỗi xảy ra khi tạo bài tập: ${err.message}`);
                            }
                        });

                        // Hàm hiển thị danh sách bài kiểm tra và bài tập
                        async function getAllTasksOfCourse(courseId) {
                            try {
                                const res = await fetch(`/api/classrooms/getAllLecturerTasksOfCourse/${lecturerId}/${courseId}`, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Authorization': `Bearer ${token}`
                                    }
                                });
                                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                                const data = await res.json();
                                const examContainer = document.getElementById('exam-container');
                                const assignmentContainer = document.getElementById('assignment-container');
                                examContainer.innerHTML = '';
                                assignmentContainer.innerHTML = '';
                                data.exams.forEach(exam => {
                                    const isPending = exam.status === 'Pending';
                                    const badgeColor = isPending ? 'warning text-dark' : 'success';
                                    const hasScore = exam.temporary_score != null;
                                    const isSimultaneous = exam.isSimultaneous === true || exam.isSimultaneous === 1;
                                    const now = new Date();
                                    const startTime = new Date(exam.start_time);
                                    const endTime = new Date(exam.end_time);
                                    let endTimeBonus = endTime;
                                    if (!isSimultaneous) {
                                        endTimeBonus = new Date(endTime);
                                        endTimeBonus.setTime(endTimeBonus.getTime() + 30 * 24 * 60 * 60 * 1000);
                                    }
                                    const showButton = isPending && now >= startTime && now <= endTimeBonus;
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
                                                <a href="/submissions/exam/${exam.exam_id}" class="btn btn-primary w-100 mt-2">
                                                    <i class="bi bi-eye me-1"></i> Xem thêm
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
                                    const hasScore = assign.temporary_score != null;
                                    const isSimultaneous = assign.isSimultaneous === true || assign.isSimultaneous === 1;
                                    const now = new Date();

                                    const startTime = new Date(assign.start_time);
                                    const endTime = new Date(assign.end_time);

                                    let endTimeBonus = endTime;
                                    if (!isSimultaneous) {
                                        endTimeBonus = new Date(endTime);
                                        endTimeBonus.setTime(endTimeBonus.getTime() + 30 * 24 * 60 * 60 * 1000);
                                    }
                                    const showButton = isPending && now >= startTime && now <= endTimeBonus;
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
                                                <a href="/submissions/assignment/${assign.assignment_id}" class="btn btn-info text-white w-100 mt-2">
                                                    <i class="bi bi-eye me-1"></i> Xem thêm
                                                </a>` : ''}
                                            ${hasScore ? `
                                                <div class="mt-3"><strong>Điểm:</strong> ${assign.temporary_score}</div>` : ''}
                                        </div>
                                    </div>
                                `;
                                });
                            } catch (err) {
                                console.error("Lỗi khi tải bài kiểm tra và bài tập:", err);
                                document.getElementById("exam-container").innerHTML = '<p class="text-danger">Không thể tải danh sách bài kiểm tra.</p>';
                                document.getElementById("assignment-container").innerHTML = '<p class="text-danger">Không thể tải danh sách bài tập.</p>';
                            }
                        }

                        async function getCourseInfo(courseId, classId) {
                            const courseInfoDiv = document.getElementById("course-info");
                            try {
                                const [courseRes, classroom] = await Promise.all([
                                    fetch(`/api/courses/getById/${courseId}`, {
                                        headers: {
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Authorization': `Bearer ${token}`
                                        }
                                    }).then(res => {
                                        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                                        return res.json();
                                    }),
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
                                console.error("Lỗi khi tải thông tin môn học:", err);
                                courseInfoDiv.innerHTML = '<p class="text-danger">Không thể tải thông tin môn học.</p>';
                            }
                        }

                        function getClassroomInfo(classId) {
                            const token = localStorage.getItem('token');
                            return fetch(`/api/classrooms/getById/${classId}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Authorization': `Bearer ${token}`
                                }
                            })
                                .then(res => {
                                    if (!res.ok) {
                                        throw new Error(`HTTP error! status: ${res.status}`);
                                    }
                                    return res.json();
                                })
                                .then(classroom => classroom)
                                .catch(err => {
                                    console.error("Lỗi khi tải thông tin lớp học:", err);
                                    return null;
                                });
                        }
                    })
                </script>
        @endauth
        @auth('students')
            <!-- Script dành cho sinh viên -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const studentId = '{{ Auth::user()->student_id }}';
                    let courseId = null;
                    let lecturerId = null;
                    let classId = null;

                    // Lấy dữ liệu từ localStorage
                    const storedData = localStorage.getItem("list_id_course_lecturer");
                    if (storedData) {
                        try {
                            const listId = JSON.parse(storedData);
                            courseId = listId.course_id;
                            lecturerId = listId.lecturer_id;
                            classId = listId.class_id;
                            console.log('localStorage data (student):', listId);
                        } catch (e) {
                            console.error("Lỗi khi parse dữ liệu từ localStorage:", e);
                        }
                    }

                    // Nếu courseId không có, thử lấy từ API
                    if (!courseId && classId) {
                        fetch(`/api/classrooms/getById/${classId}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                            .then(res => {
                                if (!res.ok) {
                                    throw new Error(`HTTP error! status: ${res.status}`);
                                }
                                return res.json();
                            })
                            .then(classroom => {
                                if (classroom && classroom.course_id) {
                                    courseId = classroom.course_id;
                                    lecturerId = classroom.lecturer_id;
                                    console.log('courseId from API (student):', courseId);
                                } else {
                                    console.warn('Không thể lấy course_id từ API');
                                }
                            })
                            .catch(err => {
                                console.error('Lỗi khi lấy thông tin lớp học:', err);
                            })
                            .finally(() => {
                                initializePage();
                            });
                    } else {
                        // Fallback: Lấy từ URL
                        const urlParams = new URLSearchParams(window.location.search);
                        courseId = courseId || urlParams.get('course_id');
                        lecturerId = lecturerId || urlParams.get('lecturer_id');
                        classId = classId || urlParams.get('class_id');
                        initializePage();
                    }

                    // Hàm khởi tạo trang
                    function initializePage() {
                        if (!courseId || !lecturerId || !classId) {
                            document.getElementById("course-info").innerHTML = '<p class="text-danger">Không thể tải thông tin môn học do thiếu dữ liệu lớp học.</p>';
                            document.getElementById("lecturer-info").innerHTML = '<p class="text-danger">Không thể tải thông tin giảng viên.</p>';
                            document.getElementById("exam-container").innerHTML = '<p class="text-danger">Không thể tải danh sách bài kiểm tra.</p>';
                            document.getElementById("assignment-container").innerHTML = '<p class="text-danger">Không thể tải danh sách bài tập.</p>';
                            return;
                        }

                        getCourseInfo(courseId, classId);
                        getLecturerInfo(lecturerId);
                        getAllStudentTasksOfCourse(studentId, courseId);
                    }

                    async function getCourseInfo(courseId, classId) {
                        const courseInfoDiv = document.getElementById("course-info");

                        try {
                            const [courseRes, classroom] = await Promise.all([
                                fetch(`/api/courses/getById/${courseId}`, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                }).then(res => {
                                    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                                    return res.json();
                                }),
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
                            console.error("Lỗi khi tải thông tin môn học:", err);
                            courseInfoDiv.innerHTML = '<p class="text-danger">Không thể tải thông tin môn học.</p>';
                        }
                    }

                    function getLecturerInfo(lecturerId) {
                        const lecturerInfoDiv = document.getElementById("lecturer-info");

                        fetch(`/api/lecturers/getById/${lecturerId}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                            .then(res => {
                                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                                return res.json();
                            })
                            .then(lecturer => {
                                lecturerInfoDiv.innerHTML = `
                                                                                    <p><strong>Tên giáo viên:</strong> ${lecturer.fullname || 'Không có dữ liệu'}</p>
                                                                                    <p><strong>Email:</strong> ${lecturer.school_email || 'Không có dữ liệu'}</p>
                                                                                    <p><strong>Email cá nhân:</strong> ${lecturer.personal_email || 'Không có dữ liệu'}</p>
                                                                                    <p><strong>Số điện thoại:</strong> ${lecturer.phone || 'Không có dữ liệu'}</p>
                                                                                `;
                            })
                            .catch(err => {
                                console.error("Lỗi khi tải thông tin giảng viên:", err);
                                lecturerInfoDiv.innerHTML = '<p class="text-danger">Không thể tải thông tin giảng viên.</p>';
                            });
                    }

                    function getClassroomInfo(classId) {
                        return fetch(`/api/classrooms/getById/${classId}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                            .then(res => {
                                if (!res.ok) {
                                    throw new Error(`HTTP error! status: ${res.status}`);
                                }
                                return res.json();
                            })
                            .then(classroom => {
                                return classroom;
                            })
                            .catch(err => {
                                console.error("Lỗi khi tải thông tin lớp học:", err);
                                return null;
                            });
                    }

                    async function getAllStudentTasksOfCourse(studentId, courseId) {
                        try {
                            const res = await fetch(`/api/getAllStudentTasksOfCourse/${studentId}/${courseId}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                            const data = await res.json();
                            const examContainer = document.getElementById('exam-container');
                            const assignmentContainer = document.getElementById('assignment-container');

                            examContainer.innerHTML = '';
                            assignmentContainer.innerHTML = '';

                            data.exams.forEach(exam => {
                                const isPending = exam.status === 'Pending';
                                const badgeColor = isPending ? 'warning text-dark' : 'success';
                                const hasScore = exam.temporary_score != null;
                                const isSimultaneous = exam.isSimultaneous === true || exam.isSimultaneous === 1;

                                const now = new Date();
                                const startTime = new Date(exam.start_time);
                                const endTime = new Date(exam.end_time);

                                let endTimeBonus = endTime;
                                if (!isSimultaneous) {
                                    endTimeBonus = new Date(endTime);
                                    endTimeBonus.setTime(endTimeBonus.getTime() + 30 * 24 * 60 * 60 * 1000);
                                }
                                const showButton = isPending && !isNaN(startTime) && !isNaN(endTimeBonus) && now >= startTime && now <= endTimeBonus;

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
                                const hasScore = assign.temporary_score != null;
                                const isSimultaneous = assign.isSimultaneous === true || assign.isSimultaneous === 1;

                                const now = new Date();
                                const startTime = new Date(assign.start_time);
                                const endTime = new Date(assign.end_time);

                                let endTimeBonus = endTime;
                                if (!isSimultaneous) {
                                    endTimeBonus = new Date(endTime);
                                    endTimeBonus.setTime(endTimeBonus.getTime() + 30 * 24 * 60 * 60 * 1000);
                                }
                                const showButton = isPending && !isNaN(startTime) && !isNaN(endTimeBonus) && now >= startTime && now <= endTimeBonus;

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
                            document.getElementById("exam-container").innerHTML = '<p class="text-danger">Không thể tải danh sách bài kiểm tra.</p>';
                            document.getElementById("assignment-container").innerHTML = '<p class="text-danger">Không thể tải danh sách bài tập.</p>';
                        }
                    }
                });
            </script>
        @endauth
@endsection
