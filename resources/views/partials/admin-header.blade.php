<div class="d-flex align-items-center px-4 py-2 flex-wrap" style="background-color: white; position: relative;">
    <img src="{{ asset('images/pantasLogo.png') }}" alt="MMFC Logo" class="header-logo-img" />
    <h1 class="school-name mb-0 ms-2"></h1>

    <button id="customMenuToggle" class="d-md-none toggle-btn" type="button">&#9776;</button>

    <div id="routeWrapper" class="d-flex gap-2 flex-wrap ms-auto responsive-nav">
        <button id="customMenuClose" class="d-md-none close-btn" type="button">&times;</button>

        <a href="{{ route('book.index') }}" class="btn0 btn-sm">Home</a>
        <a class="btn2 btn-sm" href="{{ route('attendance.scan') }}">Attendance</a>
        <a class="btn2 btn-sm" href="{{ route('attendance_logs.index') }}">Attendance Logs</a>
        <a class="btn2 btn-sm" href="{{ route('students.report') }}">ID Generation</a>
        <a href="{{ route('files.index') }}" class="btn4 btn-sm" hidden>Repository</a>
        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="btn5">Logout</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('customMenuToggle');
        const closeBtn = document.getElementById('customMenuClose');
        const routeWrapper = document.getElementById('routeWrapper');
        if (!toggleBtn || !closeBtn || !routeWrapper) return;

        toggleBtn.addEventListener('click', () => routeWrapper.classList.add('open'));
        closeBtn.addEventListener('click', () => routeWrapper.classList.remove('open'));
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) routeWrapper.classList.remove('open');
        });
    });
</script>
