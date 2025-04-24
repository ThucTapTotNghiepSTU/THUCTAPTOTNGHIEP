<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ScoresController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ListQuestionController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\SubListController;
use App\Http\Controllers\SubListQuestionController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\ITCourseController;
use App\Http\Controllers\LecturerAssignmentController;
use App\Http\Controllers\StudentTaskController;
use App\Http\Controllers\TempAnswersController;

// API lấy thông tin user đang đăng nhập
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API cho Exam
Route::prefix('exams')->group(function () {
    Route::get('/', [ExamController::class, 'index']);
    Route::post('/create', [ExamController::class, 'store']);
    Route::get('/getById/{id}', [ExamController::class, 'show']);
    Route::put('/update/{id}', [ExamController::class, 'update']);
    Route::delete('/delete/{id}', [ExamController::class, 'destroy']);
});

// API cho Score
Route::prefix('score')->group(function () {
    Route::get('/', [ScoresController::class, 'index']);
    Route::post('/create', [ScoresController::class, 'store']);
    Route::get('/getById/{id}', [ScoresController::class, 'show']);
    Route::get('/getAllScoresStudentByStudentId/{studentId}', [ScoresController::class, 'getAllScoresStudentByStudentId']);
    Route::put('/update/{id}', [ScoresController::class, 'update']);
    Route::delete('/delete/{id}', [ScoresController::class, 'destroy']);
});

// API cho Answer
Route::prefix('answer')->group(function () {
    Route::get('/', [AnswerController::class, 'index']);
    Route::post('/create', [AnswerController::class, 'store']);
    Route::get('/getById/{id}', [AnswerController::class, 'show']);
    Route::put('/update/{id}', [AnswerController::class, 'update']);
    Route::delete('/delete/{id}', [AnswerController::class, 'destroy']);
});

// API cho Submission
Route::prefix('submissions')->group(function () {
    Route::get('/', [SubmissionController::class, 'index']);
    Route::post('/create', [SubmissionController::class, 'store']);
    Route::get('/getById/{id}', [SubmissionController::class, 'show']);
    Route::put('/update/{id}', [SubmissionController::class, 'update']);
    Route::delete('/delete/{id}', [SubmissionController::class, 'destroy']);
    Route::get('/byteacher/{teacher_id}', [SubmissionController::class, 'getByTeacher']);
});

// API cho Student
Route::prefix('students')->group(function () {
    Route::get('/', [StudentController::class, 'index']);
    Route::post('/create', [StudentController::class, 'store']);
    Route::get('/getById/{id}', [StudentController::class, 'show']);
    Route::put('/update/{id}', [StudentController::class, 'update']);
    Route::delete('/delete/{id}', [StudentController::class, 'destroy']);
    Route::post('/import-students', [StudentController::class, 'importStudents']);
    Route::post('/getScoresStudentByStudentIdAndCoureId/{studentId}/{courseId}', [StudentController::class, 'getScoresStudentByStudentIdAndCoureId']);
});

// API cho Course
Route::prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'index']);
    Route::post('/create', [CourseController::class, 'store']);
    Route::get('/getById/{id}', [CourseController::class, 'show']);
    Route::get('/getCourseOfStudent/{student_id}', [CourseController::class, 'showCourseOfStudent']);
    Route::put('/update/{id}', [CourseController::class, 'update']);
    Route::delete('/delete/{id}', [CourseController::class, 'destroy']);
});

// API cho Lecturer
Route::prefix('lecturers')->group(function () {
    Route::get('/', [LecturerController::class, 'index']);
    Route::get('/{id}/classrooms', [LecturerController::class, 'getClassrooms']);
    Route::post('/create', [LecturerController::class, 'store']);
    Route::get('/getById/{id}', [LecturerController::class, 'show']);
    Route::put('/update/{id}', [LecturerController::class, 'update']);
    Route::delete('/delete/{id}', [LecturerController::class, 'destroy']);
});

// API cho ListQuestion
Route::prefix('list-questions')->group(function () {
    Route::get('/', [ListQuestionController::class, 'index']);
    Route::get('/detail/{list_question_id}', [ListQuestionController::class, 'showDetailQuestion']);
    Route::post('/create', [ListQuestionController::class, 'store']);
    Route::get('/getById/{id}', [ListQuestionController::class, 'show']);
    Route::put('/update/{id}', [ListQuestionController::class, 'update']);
    Route::delete('/delete/{id}', [ListQuestionController::class, 'destroy']);
    Route::get('/{lecturer_id}', [ListQuestionController::class, 'getAllListQuestionsWithLecturer']);
    Route::get('/topics/{course_id}', [ListQuestionController::class, 'getTopicsByCourse']);
    Route::get('/questions/by-course-topic', [ListQuestionController::class, 'getQuestionsByCourseAndTopic']);
});

// API cho Options
Route::prefix('options')->group(function () {
    Route::get('/', [OptionsController::class, 'index']);
    Route::post('/create', [OptionsController::class, 'store']);
    Route::get('/getById/{id}', [OptionsController::class, 'show']);
    Route::put('/update/{id}', [OptionsController::class, 'update']);
    Route::delete('/delete/{id}', [OptionsController::class, 'destroy']);
});

// API cho Question
Route::prefix('questions')->group(function () {
    Route::get('/', [QuestionController::class, 'index']);
    Route::post('/create', [QuestionController::class, 'store']);
    Route::post('/batch', [QuestionController::class, 'storeBatch']);
    Route::get('/getById/{id}', [QuestionController::class, 'show']);
    Route::put('/update/{id}', [QuestionController::class, 'update']);
    Route::delete('/delete/{id}', [QuestionController::class, 'destroy']);
});

// API cho StudentClass
Route::prefix('student-classes')->group(function () {
    Route::get('/', [StudentClassController::class, 'index']);
    Route::post('/create', [StudentClassController::class, 'store']);
    Route::get('/getById/{id}', [StudentClassController::class, 'show']);
    Route::put('/update/{id}', [StudentClassController::class, 'update']);
    Route::delete('/delete/{id}', [StudentClassController::class, 'destroy']);
});

// API cho SubList
Route::prefix('sub-lists')->group(function () {
    Route::get('/', [SubListController::class, 'index']);
    Route::get('/getAll/{subListId}', [SubListController::class, 'getAllSublist']);
    Route::get('/{sublistsId}', [SubListController::class, 'getAll']);
    Route::post('/create', [SubListController::class, 'store']);
    Route::get('/getById/{id}', [SubListController::class, 'show']);
    Route::put('/update/{id}', [SubListController::class, 'update']);
    Route::delete('/delete/{id}', [SubListController::class, 'destroy']);
    Route::get('/available-questions/{listQuestionId}', [SubListController::class, 'getAvailableQuestions']);
    Route::get('/by-lecturer/{lecturerId}', [SubListController::class, 'getAllByLecturer']);
    Route::get('/lecturer/chi_tiet_ma_de/{sub_list_id}', [SubListController::class, 'show'])->middleware('auth');
});

// API cho SubListQuestion
Route::prefix('sub-list-questions')->group(function () {
    Route::get('/', [SubListQuestionController::class, 'index']);
    Route::post('/create', [SubListQuestionController::class, 'store']);
    Route::get('/getById/{id}', [SubListQuestionController::class, 'show']);
    Route::put('/update/{id}', [SubListQuestionController::class, 'update']);
    Route::delete('/delete/{id}', [SubListQuestionController::class, 'destroy']);
});


// API cho Assignment
Route::prefix('assignments')->group(function () {
    Route::get('/', [AssignmentController::class, 'index']);
    Route::post('/create', [AssignmentController::class, 'store']);
    Route::post('/createAss', [AssignmentController::class, 'storeAss']);
    Route::get('/getById/{id}', [AssignmentController::class, 'show']);
    Route::put('/update/{id}', [AssignmentController::class, 'update']);
    Route::delete('/delete/{id}', [AssignmentController::class, 'destroy']);
});

// API cho Classroom
Route::prefix('classrooms')->group(function () {
    Route::get('/', [ClassroomController::class, 'index']);
    Route::get('/search', [ClassroomController::class, 'search']);
    Route::get('/filters', [ClassroomController::class, 'getFilters']);
    Route::post('/create', [ClassroomController::class, 'store']);
    Route::get('/getById/{id}', [ClassroomController::class, 'show']);
    Route::put('/update/{id}', [ClassroomController::class, 'update']);
    Route::delete('/delete/{id}', [ClassroomController::class, 'destroy']);
    Route::get('/student-classes/{student_code}', [ClassroomController::class, 'getStudentClasses']);
});

// Nhóm routes cho sinh viên
Route::prefix('student')->group(function () {
    // Xem danh sách bài thi
    Route::get('/exams/{student_id}', [StudentAssignmentController::class, 'getExams']);

    // Xem danh sách bài tập
    Route::get('/assignments/{student_id}', [StudentAssignmentController::class, 'getAssignments']);

    // Nộp bài tập/bài thi (file)
    Route::post('/submit', [StudentAssignmentController::class, 'submitWork']);

    // Nộp câu trả lời cho các câu hỏi
    Route::post('/submit-answers', [StudentAssignmentController::class, 'submitWorkAndAnswers']);

    // Xem trạng thái bài làm
    Route::get('/submission-status/{student_id}', [StudentAssignmentController::class, 'getSubmissionStatus']);

    // Xem điểm bài thi, bài tập
    Route::get('/scores/{student_id}', [StudentAssignmentController::class, 'getScores']);

    // Lấy danh sách câu hỏi cho bài thi hoặc bài tập
    Route::get('/questions', [StudentAssignmentController::class, 'getQuestions']);

    // Thêm CRUD Submission
    Route::get('/submissions', [StudentAssignmentController::class, 'listSubmissions']); // ?student_id=...&type=assignment|exam&target_id=...
    Route::post('/submissions', [StudentAssignmentController::class, 'storeSubmission']);
    Route::get('/submissions/{id}', [StudentAssignmentController::class, 'showSubmission']);
    Route::put('/submissions/{id}', [StudentAssignmentController::class, 'updateSubmission']);
    Route::delete('/submissions/{id}', [StudentAssignmentController::class, 'deleteSubmission']);

    // lưu đáp án tạp thời
    Route::post('/answers/temp', [TempAnswersController::class, 'saveTemp']);
    Route::get('/temp-answer/{id}/{studentId}', [TempAnswersController::class, 'getTempAnswers']);
    Route::delete('/answers/temp/delete', [TempAnswersController::class, 'deleteTempAnswers']);
});
Route::prefix('it-courses')->group(function () {
    Route::get('/', [ITCourseController::class, 'index']);
    Route::get('/search', [ITCourseController::class, 'search']);
    Route::get('/{id}/books', [ITCourseController::class, 'getRecommendedBooks']);
});

// Các route không cần đăng nhập (có thể truyền lecturer_id qua tham số)
// /api/lecturer-student/assignments?lecturer_id=LEC001
// /api/lecturer-student/exams?lecturer_id=LEC001&type=Trắc nghiệm
Route::prefix('lecturer-student')->group(function () {
        //  // Lấy bài tập theo giảng viên
        //  Route::get('/assignment/{lecturer_id}', [LecturerAssignmentController::class, 'getAssignments']);

        //  // Lấy bài kiểm tra theo giảng viên
        //  Route::get('/exam/{lecturer_id}', [LecturerAssignmentController::class, 'getExams']);
    ;
    // Lấy danh sách bài tập theo giảng viên
    Route::get('/assignments', [LecturerAssignmentController::class, 'getAssignments']);

    // Lấy danh sách bài kiểm tra theo giảng viên
    Route::get('/exams', [LecturerAssignmentController::class, 'getExams']);
    // Lấy danh sách bài tập
    Route::get('/exams/{examId}', [ExamController::class, 'getExamDetail']);

    // Lấy danh sách bài kiểm tra
    Route::get('/assignments/{assignmentId}', [AssignmentController::class, 'getAssignmentDetail']);

    // Lấy tất cả bài tập và bài kiểm tra theo khóa học
    Route::get('/all-by-course', [LecturerAssignmentController::class, 'getAllAssignmentsAndExamsByCourse']);

    // Lấy thống kê nộp bài
    Route::get('/submission-stats', [LecturerAssignmentController::class, 'getSubmissionStats']);
});



Route::get('/assignments-test', [AssignmentController::class, 'getAllAssignments']);
Route::get('/exams-test', [AssignmentController::class, 'getAllExams']);

Route::get('/getAllExamsAndAssignments/{studentId}', [StudentTaskController::class, 'getAllStudentTasks']);
Route::get('/getAllStudentTasksOfCourse/{studentId}/{courseId}', [StudentTaskController::class, 'getAllStudentTasksOfCourse']);
Route::post('/submissions/{id}/grade', [SubmissionController::class, 'gradeSubmission']);
