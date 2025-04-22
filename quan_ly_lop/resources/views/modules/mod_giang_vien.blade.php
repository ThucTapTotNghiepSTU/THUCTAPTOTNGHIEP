@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Phần tìm kiếm và bộ lọc -->
            <div class="search-filter-container mb-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control search-input" 
                                   id="searchKeyword"
                                   placeholder="Tìm kiếm giảng viên...">
                            <button class="btn btn-primary" id="searchBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="departmentFilter">
                            <option value="">Tất cả khoa</option>
                            <option value="cntt">Công nghệ thông tin</option>
                            <option value="kt">Kinh tế</option>
                            <option value="nn">Ngoại ngữ</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Danh sách giảng viên -->
            <div class="lecturers-container mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="section-title">Danh sách giảng viên</h3>
                </div>

                <div class="row g-4" id="searchResults">
                    @foreach($lecturers as $lecturer)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="lecturer-card card h-100">
                            <div class="card-body">
                                <div class="lecturer-avatar mb-3">
                                    <img src="{{ $lecturer['avatar'] }}" alt="{{ $lecturer['fullname'] }}" class="rounded-circle">
                                </div>
                                <h5 class="card-title text-center">{{ $lecturer['fullname'] }}</h5>
                                <p class="text-center text-muted mb-3">{{ $lecturer['position'] }}</p>
                                <div class="lecturer-info">
                                    <div class="info-item">
                                        <i class="fas fa-graduation-cap"></i>
                                        <span>{{ $lecturer['department'] }}</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-book"></i>
                                        <span>{{ $lecturer['subjects'] }} môn giảng dạy</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-users"></i>
                                        <span>{{ $lecturer['students'] }} sinh viên</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-envelope"></i>
                                        <span>{{ $lecturer['email'] }}</span>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('lecturer.detail', $lecturer['id']) }}" class="btn btn-outline-primary">
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.search-filter-container {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
}

.search-input {
    height: 45px;
    border-radius: 10px 0 0 10px !important;
    border: 2px solid #e0e0e0;
    border-right: none;
}

.search-input:focus {
    box-shadow: none;
    border-color: #007bff;
}

.input-group .btn {
    border-radius: 0 10px 10px 0;
    padding: 0 20px;
}

.form-select {
    height: 45px;
    border-radius: 10px;
    border: 2px solid #e0e0e0;
}

.lecturer-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.lecturer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.lecturer-avatar {
    text-align: center;
}

.lecturer-avatar img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.lecturer-info {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin: 1rem 0;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #6c757d;
    font-size: 0.9rem;
}

.info-item i {
    color: #007bff;
    width: 20px;
}

.section-title {
    color: #2c3e50;
    font-weight: 600;
    position: relative;
    padding-bottom: 10px;
}

.section-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(45deg, #007bff, #6610f2);
    border-radius: 2px;
}

@media (max-width: 768px) {
    .lecturer-avatar img {
        width: 100px;
        height: 100px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchKeyword = document.getElementById('searchKeyword');
    const searchBtn = document.getElementById('searchBtn');
    const departmentFilter = document.getElementById('departmentFilter');
    const searchResults = document.getElementById('searchResults');
    
    let searchTimeout = null;
    
    function performSearch() {
        const keyword = searchKeyword.value;
        const department = departmentFilter.value;
        
        fetch(`/api/lecturers/search?keyword=${encodeURIComponent(keyword)}&department=${encodeURIComponent(department)}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    displayResults(data.data);
                }
            })
            .catch(error => {
                console.error('Lỗi khi tìm kiếm:', error);
            });
    }
    
    function displayResults(lecturers) {
        if (lecturers.length === 0) {
            searchResults.innerHTML = `
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Không tìm thấy giảng viên nào</h4>
                </div>
            `;
            return;
        }
        
        searchResults.innerHTML = lecturers.map(lecturer => `
            <div class="col-12 col-md-6 col-lg-4">
                <div class="lecturer-card card h-100">
                    <div class="card-body">
                        <div class="lecturer-avatar mb-3">
                            <img src="${lecturer.avatar || '/images/default-avatar.jpg'}" 
                                 alt="${lecturer.fullname}" 
                                 class="rounded-circle">
                        </div>
                        <h5 class="card-title text-center">${lecturer.fullname}</h5>
                        <p class="text-center text-muted mb-3">${lecturer.position}</p>
                        <div class="lecturer-info">
                            <div class="info-item">
                                <i class="fas fa-graduation-cap"></i>
                                <span>${lecturer.department}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-book"></i>
                                <span>${lecturer.subjects} môn giảng dạy</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-users"></i>
                                <span>${lecturer.students} sinh viên</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-envelope"></i>
                                <span>${lecturer.email}</span>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/lecturers/${lecturer.id}" class="btn btn-outline-primary">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    // Event listeners
    searchKeyword.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300);
    });
    searchBtn.addEventListener('click', performSearch);
    departmentFilter.addEventListener('change', performSearch);
});
</script>
@endsection 