<!DOCTYPE html>
<html>
<head>
    <title>Register Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('public/css/students/create.css') }}">
</head>
<body>

    <!-- Header with Left Logo and Right Logout Button -->
    <div class="d-flex align-items-center px-4 py-2 flex-wrap" style="background-color: white;">
        <img src="{{ asset('images/pantasLogo.png') }}" alt="New Logo" class="header-logo-img" />
        <h1 class="school-name mb-0 ms-2"></h1>
    
        <!-- IMPORTANT: add ms-auto to push right -->
        <div class="d-flex gap-2 flex-wrap ms-auto" style="margin-right: 9rem;">
            <a href="{{ route('book.index') }}" class="btn0 btn-sm">Home</a>
            <a href="{{ route('attendance.scan') }}" class="btn7 btn-sm">Attendance</a>
            <a href="{{ route('attendance_logs.index') }}" class="btn6 btn-sm">Attendance-logs</a>
            
            <a hidden href="{{ route('prospectus.index') }}" class="btn8 btn-sm">Prospectus Manager</a>
            <a href="{{ route('logs.index') }}" class="btn4 btn-sm" hidden>Logs</a>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn5">Logout</button>
            </form>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">

                
                    </a>
                </div>

                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <h4 class="mb-0">Register Student</h4>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="lastname" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="firstname" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3" hidden>
                                    <label class="form-label">QR Code</label>
                                    <input type="text" name="qrcode" class="form-control" >
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Role</label>
                                    <div class="position-relative">
                                        <select name="role_id" class="form-select" required>
                                            <option value="" disabled selected>Select Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ ucfirst($role->description) }}</option>
                                            @endforeach
                                        </select>
                                        <i class="fa fa-caret-down position-absolute" 
                                           style="top: 50%; right: 15px; transform: translateY(-50%); pointer-events: none;"></i>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                <label class="form-label">Course</label>
                                <div class="position-relative">
                                    <select name="course" class="form-select" required>
                                        <option value="" disabled selected>Select Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course }}">{{ $course }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fa fa-caret-down position-absolute" style="top: 50%; right: 15px; transform: translateY(-50%); pointer-events: none;"></i>
                                </div>
                            </div>

                                <div class="col-md-6 mb-3">
    <label class="form-label">Year</label>
    <div class="position-relative">
        <select name="year" class="form-select" required>
            <option value="" disabled selected>Select Year</option>
            <option value="First Year">First Year</option>
            <option value="Second Year">Second Year</option>
            <option value="Third Year">Third Year</option>
            <option value="Fourth Year">Fourth Year</option>
            <option value="Fifth Year">Fifth Year</option>
        </select>
        <!-- Optional: font-awesome icon on top of native caret -->
        <i class="fa fa-caret-down position-absolute" style="top: 50%; right: 15px; transform: translateY(-50%); pointer-events: none;"></i>
    </div>
</div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Profile Picture</label>
                                    <input type="file" name="profile_picture" class="form-control">
                                </div>
                            </div>
                            <div class="buttons">
                            <a href="{{ route('students.index') }}" class="btn-bck btn-back">
                                Back
                            </a>
                            <button type="submit" class="btn-register" style ="border:none;">Register</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

   
</body>
</html>
