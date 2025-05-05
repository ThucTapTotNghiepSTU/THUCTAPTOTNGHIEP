@extends('templates.template_normal')

@section('title', 'Danh sách bài nộp')

@section('main-content')
    <div class="container mx-auto py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">
                {{ isset($exam) ? 'Bài kiểm tra: ' . $exam->title : 'Bài tập: ' . $assignment->title }}
            </h1>

            <div>
                <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Quay lại
                </a>
            </div>
        </div>

        @include('modules.mod_submissions_list', [
            'submissions' => $submissions ?? collect([]),
            'exam_id' => $exam->exam_id ?? null,
            'assignment_id' => $assignment->assignment_id ?? null
        ])
    </div>
@endsection
