document.addEventListener('DOMContentLoaded', function() {
    const searchKeyword = document.getElementById('searchKeyword');
    const searchBtn = document.getElementById('searchBtn');
    const letterFilter = document.getElementById('letterFilter');
    const sortOrder = document.getElementById('sortOrder');
    const searchResults = document.getElementById('searchResults');
    const letterTags = document.querySelectorAll('.letter-tag');
    
    let searchTimeout = null;
    
    // Hàm tìm kiếm với debounce
    function debounceSearch(func, wait) {
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(searchTimeout);
                func(...args);
            };
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(later, wait);
        };
    }
    
    // Hàm tìm kiếm chính
    const performSearch = debounceSearch(() => {
        const keyword = searchKeyword.value;
        const letter = letterFilter.value;
        const sort = sortOrder.value;
        
        // Hiển thị loading
        searchResults.innerHTML = `
            <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang tìm kiếm...</span>
                </div>
            </div>
        `;
        
        // Tạo URL tìm kiếm
        const searchParams = new URLSearchParams();
        if (keyword) searchParams.append('keyword', keyword);
        if (letter) searchParams.append('letter', letter);
        if (sort) searchParams.append('sort', sort);
        
        // Gọi API
        fetch(`/api/classrooms/search?${searchParams.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    displayResults(data.data);
                }
            })
            .catch(error => {
                console.error('Lỗi khi tìm kiếm:', error);
            });
    });

    // Hàm hiển thị kết quả
    function displayResults(classes) {
        searchResults.innerHTML = '';
        
        if (classes.length === 0) {
            searchResults.innerHTML = '<div class="col-12"><p class="text-center">Không tìm thấy kết quả nào</p></div>';
            return;
        }

        classes.forEach(classroom => {
            const classCard = `
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="class-card card h-100">
                        <div class="card-body">
                            <h5 class="card-title">${classroom.class_code}</h5>
                            <p class="card-text">${classroom.class_description || 'Không có mô tả'}</p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Giảng viên: ${classroom.lecturer.fullname}<br>
                                    Môn học: ${classroom.course.course_name}
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            `;
            searchResults.innerHTML += classCard;
        });
    }

    // Đăng ký sự kiện
    searchBtn.addEventListener('click', performSearch);
    searchKeyword.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    letterFilter.addEventListener('change', performSearch);
    sortOrder.addEventListener('change', performSearch);

    // Tìm kiếm ban đầu khi trang được tải
    performSearch();
}); 
