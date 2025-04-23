<html>

@include('widgets.head')
@auth
    <meta name="student_id" content="{{ Auth::user()->student_id }}">
    <meta name="lecturer_id" content="{{ Auth::user()->lecturer_id }}">
@endauth

<body>

    @include('widgets.headerLecture')

    @yield('main-content')

    @include('widgets.footer')

</body>

</html>
