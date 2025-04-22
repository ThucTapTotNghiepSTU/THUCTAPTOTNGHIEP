@php
// Determine whether we're viewing multiple choice or essay
$type = isset($is_multiple_choice) && $is_multiple_choice ? 'trắc nghiệm' : 'tự luận';

// If no exams data passed, use empty collection
if (!isset($exams)) {
    $exams = collect([]);
}
@endphp

<div class="p-4 bg-white rounded-lg shadow-md">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-xl font-bold text-gray-800">Danh sách bài <span class="text-blue-600">({{ $type }})</span></h2>

        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <div class="relative flex-grow">
                <input type="text" id="search-input" placeholder="Tìm kiếm bài kiểm tra..."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <span class="absolute right-3 top-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-gray-400" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                    </svg>
                </span>
            </div>

            <select id="status-filter" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">Tất cả trạng thái</option>
                <option value="active">Đang mở</option>
                <option value="upcoming">Sắp mở</option>
                <option value="closed">Đã đóng</option>
            </select>
        </div>
    </div>

    @if($exams->count() > 0)
    <div class="overflow-x-auto bg-white rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên bài</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian mở</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian đóng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời lượng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tình trạng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="exams-table-body">
                @foreach($exams as $index => $exam)
                @php
                $now = \Carbon\Carbon::now();
                $startTime = \Carbon\Carbon::parse($exam->start_time);
                $endTime = \Carbon\Carbon::parse($exam->end_time);
                
                if ($now < $startTime) {
                    $status = 'upcoming';
                    $statusText = 'Sắp mở';
                    $statusClass = 'bg-blue-100 text-blue-800';
                } elseif ($now <= $endTime) {
                    $status = 'active';
                    $statusText = 'Đang mở';
                    $statusClass = 'bg-green-100 text-green-800';
                } else {
                    $status = 'closed';
                    $statusText = 'Đã đóng';
                    $statusClass = 'bg-gray-100 text-gray-800';
                }
                
                // Calculate duration in minutes
                $durationMinutes = $startTime->diffInMinutes($endTime);
                $hours = floor($durationMinutes / 60);
                $minutes = $durationMinutes % 60;
                $durationText = '';
                if ($hours > 0) {
                    $durationText .= $hours . ' giờ ';
                }
                if ($minutes > 0 || $hours == 0) {
                    $durationText .= $minutes . ' phút';
                }
                @endphp
                <tr class="hover:bg-gray-50 exam-row transition-colors"
                    data-status="{{ $status }}"
                    data-name="{{ $exam->title ?? $exam->name ?? '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <div class="font-medium">{{ $exam->title ?? $exam->name ?? 'Chưa đặt tên' }}</div>
                        <div class="text-xs text-gray-500">{{ $exam->is_multiple_choice ? 'Trắc nghiệm' : 'Tự luận' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($exam->start_time)->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($exam->end_time)->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $durationText }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('exams.show', $exam->id) }}" 
                               class="text-blue-600 hover:text-blue-900 hover:underline flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Xem
                            </a>
                            @if($status != 'closed')
                            <a href="{{ route('exams.edit', $exam->id) }}" 
                               class="text-indigo-600 hover:text-indigo-900 hover:underline flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Sửa
                            </a>
                            @endif
                            <a href="{{ route('submissions.index', ['type' => 'exam', 'id' => $exam->id]) }}" 
                               class="text-green-600 hover:text-green-900 hover:underline flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Bài nộp
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 text-right text-sm text-gray-600">
        Tổng số: <span class="font-medium">{{ $exams->count() }}</span> bài {{ $type }}
    </div>
    @else
    <div class="bg-gray-50 rounded-lg p-8 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <p class="text-gray-500 mb-2">Chưa có bài {{ $type }} nào.</p>
        <p class="text-sm text-gray-400">Vui lòng tạo bài {{ $type }} mới để hiển thị tại đây.</p>
    </div>
    @endif

    <!-- Hiển thị phân trang (nếu cần) -->
    @if(isset($exams) && method_exists($exams, 'links') && $exams->hasPages())
    <div class="mt-4">
        {{ $exams->links() }}
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        const rows = document.querySelectorAll('.exam-row');
        const tableBody = document.getElementById('exams-table-body');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            
            let visibleCount = 0;

            rows.forEach(row => {
                const examName = row.getAttribute('data-name').toLowerCase();
                const status = row.getAttribute('data-status');

                const matchesSearch = examName.includes(searchTerm);
                const matchesStatus = statusValue === 'all' || status === statusValue;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Nếu không có kết quả, hiển thị thông báo
            if (visibleCount === 0 && rows.length > 0) {
                if (!document.getElementById('no-results-message')) {
                    const noResults = document.createElement('tr');
                    noResults.id = 'no-results-message';
                    noResults.innerHTML = `
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p>Không tìm thấy kết quả phù hợp</p>
                        </td>
                    `;
                    tableBody.appendChild(noResults);
                }
            } else {
                const noResults = document.getElementById('no-results-message');
                if (noResults) {
                    noResults.remove();
                }
            }
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
    });
</script>