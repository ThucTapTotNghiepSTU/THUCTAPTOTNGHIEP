@extends('templates.template_lecture')

@section('main-content')
<style>
    /* CSS tùy chỉnh cho trang chi tiết mã đề */
    .sublist-container {
        max-width: 100%;
        padding: 20px;
    }

    .sublist-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .sublist-header h2 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #343a40;
        margin: 0;
    }

    .sublist-details {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .sublist-details p {
        margin: 0 0 10px;
        font-size: 1rem;
        color: #495057;
    }

    .sublist-details p strong {
        color: #212529;
    }

    .questions-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
    }

    .questions-section h5 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 15px;
    }

    .question-item {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        transition: box-shadow 0.2s ease;
    }

    .question-item:hover {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .question-item strong {
        font-size: 1.1rem;
        color: #212529;
    }

    .question-item p {
        margin: 5px 0;
        font-size: 0.95rem;
        color: #495057;
    }

    .question-item p strong {
        color: #343a40;
    }

    .options-list {
        margin-top: 10px;
        padding-left: 20px;
    }

    .options-list li {
        font-size: 0.9rem;
        color: #495057;
        margin-bottom: 5px;
    }

    .options-list .text-success {
        font-weight: 500;
    }

    .back-button {
        display: inline-block;
        margin-top: 20px;
    }

    /* Responsive cho mobile */
    @media (max-width: 768px) {
        .sublist-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .sublist-header h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .sublist-details {
            padding: 15px;
        }

        .questions-section {
            padding: 15px;
        }

        .question-item {
            padding: 10px;
        }

        .question-item strong {
            font-size: 1rem;
        }

        .question-item p {
            font-size: 0.9rem;
        }

        .options-list li {
            font-size: 0.85rem;
        }
    }
</style>

<div class="container py-4 sublist-container">
    <!-- Header -->
    <div class="sublist-header">
        <h2>Chi tiết mã đề: {{ $sublist->title }}</h2>
    </div>

    <!-- Thông tin mã đề -->
    <div class="sublist-details">
        <div class="row">
            <div class="col-md-6 col-12">
                <p><strong>ID mã đề:</strong> {{ $sublist->sub_list_id }}</p>
                <p><strong>Trộn câu hỏi:</strong> {{ $sublist->isShuffle ? 'Có' : 'Không' }}</p>
            </div>
            <div class="col-md-6 col-12">
                <p><strong>Thời gian tạo:</strong> {{ $sublist->created_at->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </div>

    <!-- Danh sách câu hỏi -->
    <div class="questions-section">
        <h5>Danh sách câu hỏi</h5>
        @if($sublist->questions->isEmpty())
            <p class="text-muted">Không có câu hỏi nào trong mã đề này.</p>
        @else
            <div class="questions-list">
                @foreach($sublist->questions as $question)
                    <div class="question-item">
                        <strong>{{ $question->title }}</strong> (Loại: {{ $question->type }})
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <p><strong>Nội dung:</strong> {{ $question->content }}</p>
                                <p><strong>Chủ đề:</strong> {{ $question->listQuestion->topic }}</p>
                            </div>
                            <div class="col-md-6 col-12">
                                <p><strong>Môn học:</strong> {{ $question->listQuestion->course->course_name }}</p>
                                <p><strong>Giảng viên:</strong> {{ $question->listQuestion->lecturer->fullname }}</p>
                            </div>
                        </div>

                        @if($question->type === 'Trắc nghiệm' && $question->options->isNotEmpty())
                            <p><strong>Lựa chọn:</strong></p>
                            <ul class="options-list">
                                @foreach($question->options as $option)
                                    <li>
                                        {{ $option->option_text }}
                                        @if($option->is_correct)
                                            <span class="text-success">(Đáp án đúng)</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Nút quay lại -->
    <div class="back-button">
        <button onclick="history.back()" class="btn btn-secondary">Quay lại</button>
    </div>
</div>
@endsection
