<div class="d-flex gap-3">
    <div class="mb-3">
        <label for="exam_type" class="form-label">Kiểu đề</label>
        <select class="form-control" name="exam_type" id="exam_type" required>
            <option value="">Chọn kiểu đề</option>
            <option value="Trắc nghiệm">Đề thi trắc nghiệm</option>
            <option value="Tự luận">Đề thi tự luận</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="sub_list_id" class="form-label">Mã đề</label>
        <select class="form-control" name="sub_list_id" id="sub_list_id" required>
            <option value="">Chọn mã đề</option>
            <!-- Options sẽ được điền bằng JS từ API dựa trên loại đề -->
        </select>
    </div>
</div>

<div class="mb-3">
    <label for="title" class="form-label">Tiêu đề</label>
    <input type="text" class="form-control" name="title" required>
</div>

<div class="mb-3">
    <label for="content" class="form-label">Nội dung</label>
    <textarea class="form-control" name="content" rows="3" required></textarea>
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" name="isSimultaneous" id="isSimultaneous" value="1">
    <label class="form-check-label" for="isSimultaneous">Bài tập đồng thời</label>
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" name="show_result" id="show_result" value="1">
    <label class="form-check-label" for="show_result">Hiển thị kết quả</label>
</div>

<div class="mb-3">
    <label for="start_time" class="form-label">Thời gian bắt đầu</label>
    <input type="datetime-local" class="form-control" name="start_time" required>
</div>

<div class="mb-3">
    <label for="end_time" class="form-label">Thời gian kết thúc</label>
    <input type="datetime-local" class="form-control" name="end_time" required>
</div>

<input type="hidden" name="type" id="type"> <!-- Giá trị sẽ được set bằng JS -->
<input type="hidden" name="status" id="status">
