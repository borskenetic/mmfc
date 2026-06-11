<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Registrations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hidden { display: none; }
    </style>
</head>
<body>
<div class="container py-4">
    <h3>Pending Registrations</h3>

    @if(session('success')) 
        <div class="alert alert-success">{{ session('success') }}</div> 
    @endif
    @if(session('error')) 
        <div class="alert alert-danger">{{ session('error') }}</div> 
    @endif

    <!-- Toggle Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <button id="showStudents" class="btn btn-primary me-2">View Students</button>
            <button id="showEmployees" class="btn btn-outline-primary">View Employees</button>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">
            ← Back to Registered
        </a>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('pending.index') }}" class="mb-3">
        <input type="hidden" name="tab" value="{{ request('tab', 'students') }}">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search...">
    </form>

    <!-- 🧑‍🎓 Pending Students Table -->
    <div id="studentTable">
        <h4>Pending Student Registrations</h4>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingStudents as $p)
                    <tr>
                        <td>
                            @if($p->profile_picture)
                                <img src="{{ asset($p->profile_picture) }}" width="80">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>{{ $p->firstname }} {{ $p->lastname }}</td>
                        <td>{{ $p->course }}</td>
                        <td>{{ $p->year }}</td>
                        <td>
                            <form action="{{ route('students.approve', $p->id) }}" method="POST" style="display:inline">
                                @csrf
                                <button class="btn btn-success btn-sm">Approve</button>
                            </form>
                            <form action="{{ route('students.reject', $p->id) }}" method="POST" style="display:inline">
                                @csrf
                                <button class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No pending student registrations</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $pendingStudents->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- 🧑‍💼 Pending Employees Table -->
    <div id="employeeTable" class="hidden">
        <h4>Pending Employee Registrations</h4>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingEmployees as $e)
                    <tr>
                        <td>
                            @if($e->formal_picture)
                                <img src="{{ asset($e->formal_picture) }}" width="80">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>{{ $e->firstname }} {{ $e->lastname }}</td>
                        <td>{{ $e->department }}</td>
                        <td>{{ $e->position }}</td>
                        <td>{{ $e->status }}</td>
                        <td>
                            <form action="{{ route('employees.approve', $e->id) }}" method="POST" style="display:inline">
                                @csrf
                                <button class="btn btn-success btn-sm">Approve</button>
                            </form>
                            <form action="{{ route('employees.reject', $e->id) }}" method="POST" style="display:inline">
                                @csrf
                                <button class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No pending employee registrations</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $pendingEmployees->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
    const studentTable = document.getElementById('studentTable');
    const employeeTable = document.getElementById('employeeTable');
    const btnStudents = document.getElementById('showStudents');
    const btnEmployees = document.getElementById('showEmployees');
    const tabInput = document.querySelector('input[name="tab"]');

    // Tab toggle functions
    btnStudents.addEventListener('click', () => {
        studentTable.classList.remove('hidden');
        employeeTable.classList.add('hidden');
        btnStudents.classList.replace('btn-outline-primary', 'btn-primary');
        btnEmployees.classList.replace('btn-primary', 'btn-outline-primary');
        tabInput.value = 'students';
    });

    btnEmployees.addEventListener('click', () => {
        employeeTable.classList.remove('hidden');
        studentTable.classList.add('hidden');
        btnEmployees.classList.replace('btn-outline-primary', 'btn-primary');
        btnStudents.classList.replace('btn-primary', 'btn-outline-primary');
        tabInput.value = 'employees';
    });

    // On page load, open correct tab based on query string or default
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || 'students';
    if(tab === 'employees') {
        btnEmployees.click();
    } else {
        btnStudents.click();
    }
</script>

</body>
</html>
