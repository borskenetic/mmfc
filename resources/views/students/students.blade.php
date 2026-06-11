<!DOCTYPE html>
<html>
<head>
    <title>Registered Patrons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/css/students/students.css') }}">
</head>
<body>

<!-- Header with Left Logo and Right Logout Button -->
<div class="d-flex align-items-center px-4 py-2 flex-wrap" style="background-color: white; position: relative;">
    <img src="{{ asset('images/pantasLogo.png') }}" alt="New Logo" class="header-logo-img" />
    <h1 class="school-name mb-0 ms-2"></h1>

    <!-- Hamburger Toggle -->
    <button id="customMenuToggle" class="d-md-none toggle-btn">&#9776;</button>

    <!-- Navigation Wrapper -->
    <div id="routeWrapper" class="d-flex gap-2 flex-wrap ms-auto responsive-nav">
        <button id="customMenuClose" class="d-md-none close-btn">&times;</button>

        <a href="{{ route('book.index') }}" class="btn0 btn-sm">Home</a>
        <a class="btn2 btn-sm" href="{{ route('attendance.scan') }}">Attendance</a>
        <a class="btn2 btn-sm" href="{{ route('attendance_logs.index') }}">Attendance-logs</a>
        <a class="btn2 btn-sm" href="{{ route('students.report') }}">ID Generation</a>
        <a href="{{ route('files.index') }}" class="btn4 btn-sm" hidden>Repository</a>

        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="btn5">Logout</button>
        </form>
    </div>
</div>

<!-- ✅ Menu Toggle Script -->
<script>
    const toggleBtn = document.getElementById('customMenuToggle');
    const closeBtn = document.getElementById('customMenuClose');
    const routeWrapper = document.getElementById('routeWrapper');

    toggleBtn.addEventListener('click', () => routeWrapper.classList.add('open'));
    closeBtn.addEventListener('click', () => routeWrapper.classList.remove('open'));
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) routeWrapper.classList.remove('open');
    });
</script>

<div class="container mt-5">
    <div class="card">
        <div id="container" class="card-header text-center">
            <h4>Registered Students</h4>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-3">
                <!-- Search Form -->
                <div class="d-flex mb-2" style="max-width: 350px;">
                    <form action="{{ route('students.index') }}" method="GET" class="d-flex w-100">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search patrons..." value="{{ request('search') }}">
                        <button type="submit" id="search" class="btn btn-primary btn-sm ms-2">Search</button>
                    </form>
                </div>

                <!-- Register + Pending -->
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('students.create') }}" class="btn btn-add">+ Register Patron</a>
                    <a href="{{ route('pending.index') }}" class="btn btn-warning">View Pending Registrations</a>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="mb-3 text-center">
                <a href="{{ route('students.index') }}" class="btn btn-outline-primary btn-sm active">Students</a>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-primary btn-sm">Faculty</a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>QR Code</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>Role</th>
                            <th>Actions</th>
                            <th>Generate ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    @if($student->profile_picture)
                                        <img src="{{ asset($student->profile_picture) }}" alt="Profile" class="profile-img">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                                <td>{{ $student->lastname }}</td>
                                <td>{{ $student->firstname }}</td>
                                <td>{{ $student->qrcode }}</td>
                                <td>{{ $student->course }}</td>
                                <td>{{ $student->year }}</td>
                                <td>{{ $student->role ? $student->role->description : '—' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Options
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('students.edit', $student->id) }}">Edit</a></li>
                                            <li>
                                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item" type="submit">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Generate
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ url('idcard/front/' . $student->id) }}" target="_blank">Front</a></li>
                                            <li><a class="dropdown-item" href="{{ url('idcard/back/' . $student->id) }}" target="_blank">Back</a></li>
                                            <li><a class="dropdown-item" href="{{ url('idcard/download/' . $student->id) }}">Download ZIP</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9">No students found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    {{ $students->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <a href="{{ route('books.index') }}" id="back" class="btn btn-back mt-3">← Back to Books</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
