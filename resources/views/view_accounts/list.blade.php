<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Accounts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/css/view_accounts/list.css') }}">

</head>
<body>
    
    <div class="d-flex align-items-center px-4 py-2 flex-wrap" style="background-color: white; position: relative;">
    <img src="{{ asset('images/pantasLogo.png') }}" alt="New Logo" class="header-logo-img" />
    <h1 class="school-name mb-0 ms-2"></h1>

    <!-- Hamburger Toggle (visible only on small screens) -->
    <button id="customMenuToggle" class="d-md-none toggle-btn">
        &#9776;
    </button>

    <!-- Navigation Wrapper -->
    <div id="routeWrapper" class="d-flex gap-2 flex-wrap ms-auto responsive-nav">
        <!-- Close Button (for mobile view) -->
        <button id="customMenuClose" class="d-md-none close-btn">
            &times;
        </button>

        <a href="{{ route('book.index') }}" class="btn0 btn-sm">Home</a>

        <div class="attendance_dropdown">
            <button class="attendance_dropdown-button">Attendance</button>
            <div class="attendance_dropdown-content">
                <a href="{{ route('attendance.scan') }}">Attendance</a>
                <a href="{{ route('attendance_logs.index') }}">Attendance-logs</a>
            </div>
        </div>

        <a href="{{ route('landing') }}" class="btn2 btn-sm {{ request()->routeIs('books.landing') ? 'active-btn' : '' }}"> OPAC</a>
        <a href="{{ route('users.create') }}" class="btn3 btn-sm">Create Account</a>
         <a href="{{ route('prospectus.index') }}" class="btn3 btn-sm">Prospectus Manager</a>

        <div class="logs_dropdown">
            <button class="logs_dropdown-button">Logs</button>
            <div class="logs_dropdown-content">
                <a href="{{ route('logs.index') }}">Logs</a>
                <a href="{{ route('rfid.scanner') }}">RFID Scanner</a>
                <a href="{{ route('book.report.download') }}">Download Book Report</a>
                <a href="{{ route('students.report') }}">Student Report</a>
            </div>
        </div>
        
        <a href="https://area51lmslibrary.com/user-account/?fbclid=IwY2xjawLvE-xleHRuA2FlbQIxMABicmlkETFHTzhpTjBrRURpVWFFdW9hAR7tC4LGq_N7YomZscUpiyZKJxd0BCy69WYZuj5CxaseF8G5ctGQnauMPJnheg_aem_ZvE4NOhe8ZwtNtoumemmyg" 
           class="btn8 btn-sm" 
           target="_blank" 
           rel="noopener noreferrer" hidden>
           51 Learned
        </a>        

        <a href="{{ route('files.index') }}" class="btn4 btn-sm">Repository</a>
        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="btn5">Logout</button>
        </form>
    </div>
</div>
    
    
    
<div class="container mt-5">
    <div class="card p-4">
        <h2 class="mb-4 text-primary">User Accounts</h2>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="text-center">ID</th>
                    <th scope="col">First Name</th>
                     <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col" class="text-center">Role</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="text-center">{{ $user->id }}</td>
                        <td>{{ $user->fname }}</td>
                        <td>{{ $user->lname }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            <span class="badge badge-role badge-{{ $user->role }}">{{ $user->role }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


</body>
</html>
