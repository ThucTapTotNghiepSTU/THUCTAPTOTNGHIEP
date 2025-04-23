<html>

@include('widgets.head')

@auth
    <meta name="student_id" content="{{ Auth::user()->student_id }}">
    <meta name="lecturer_id" content="{{ Auth::user()->lecturer_id }}">
@endauth

<body>

    @auth
        @if (Auth::user()->lecturer_id)
            @include('widgets.headerLecture')
        @elseif (Auth::user()->student_id)
            @include('widgets.header')
        @endif
    @else
        @include('widgets.header') {{-- Mặc định nếu chưa đăng nhập --}}
    @endauth

    @yield('main-content')

    @include('widgets.footer')

</body>

</html>
