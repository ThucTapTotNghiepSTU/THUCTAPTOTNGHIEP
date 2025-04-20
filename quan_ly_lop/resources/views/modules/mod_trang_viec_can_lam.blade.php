@section('main-content')
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
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
        const token = localStorage.getItem('token');

        document.addEventListener("DOMContentLoaded", async () => {
            try {
                const res = await fetch(`/api/getAllExamsAndAssignments/${studentId}`);
                const data = await res.json();

                // Render Exams
                const examContainer = document.getElementById('exam-container');
                data.exams.forEach(exam => {
                    examContainer.innerHTML += `
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">${exam.title}</h5>
                                <p class="card-text">${exam.content}</p>
                                <p><strong>Tên môn học:</strong> ${exam.course_name}</p>
                                <p><strong>Loại:</strong> ${exam.type}</p>
                                <p><strong>Bắt đầu:</strong> ${exam.start_time}</p>
                                <p><strong>Kết thúc:</strong> ${exam.end_time}</p>
                                <p><strong>Điểm:</strong> {{ $exam->temporary_score ?? 'Chưa có điểm' }}</p>
                                <span class="badge bg-${exam.status === 'Pending' ? 'warning' : 'success'}">${exam.status}</span>
                            </div>
                        </div>
                    `;
                });

                // Render Assignments
                const assignmentContainer = document.getElementById('assignment-container');
                data.assignments.forEach(assign => {
                    assignmentContainer.innerHTML += `
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">${assign.title}</h5>
                                <p class="card-text">${assign.content}</p>
                                <p><strong>Tên môn học:</strong> ${assign.course_name}</p>
                                <p><strong>Loại:</strong> ${assign.type}</p>
                                <p><strong>Bắt đầu:</strong> ${assign.start_time}</p>
                                <p><strong>Kết thúc:</strong> ${assign.end_time}</p>
                                <p><strong>Điểm:</strong> {{ $assign->temporary_score ?? 'Chưa có điểm' }}</p>
                                <span class="badge bg-${assign.status === 'Pending' ? 'warning' : 'success'}">${assign.status}</span>
                            </div>
                        </div>
                    `;
                });
            } catch (error) {
                console.error("Lỗi khi gọi API:", error);
            }
        });
    </script>

@endsection
