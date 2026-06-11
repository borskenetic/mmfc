<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin File Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/file/index.css') }}">
    <style>
    </style>
</head>
<body class="p-4">
    
    <!-- Header with Left Logo and Right Logout Button -->
    <div class="d-flex align-items-center px-4 py-2 flex-wrap" style="background-color: white;">
        <img src="{{ asset('images/pantasLogo.png') }}" alt="New Logo" class="header-logo-img" />
        <h1 class="school-name mb-0 ms-2"></h1>
    
        <!-- IMPORTANT: add ms-auto to push right -->
        <div class="d-flex gap-2 flex-wrap ms-auto" style="margin-right: 9rem;">
            <a href="{{ route('book.index') }}" class="btn1 btn-sm">Home</a>
            
            
            
            
            <div class="attendance_dropdown">
                <button class="attendance_dropdown-button">Attendance</button>
                 <div class="attendance_dropdown-content">
                    <a href="{{ route('attendance.scan') }}">Attendance</a>
                 <a href="{{ route('attendance_logs.index') }}">Attendance-logs</a>
                 
              </div>
            </div>
            
            
            
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
               class="btn4 btn-sm" 
               target="_blank" 
               rel="noopener noreferrer" hidden>
               51 Learned
            </a>
            <a href="{{ route('files.index') }}" class="btn0 btn-sm">Repository</a>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn5">Logout</button>
            </form>
        </div>
    </div>
    
<div class="container">
    <h2 class="text-center mb-4" style="background-color: #22333b; color: white; padding: 20px; border-radius: 10px;"><i class="bi bi-folder2-open text-primary me-2"></i>Admin File Manager</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Upload Form -->
    <div class="upload-card mb-5">
    <form action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-folder-plus me-1"></i>Folder Name (optional)</label>
            <input type="text" name="folder" class="form-control" placeholder="e.g., Documents">
        </div>
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-upload me-1"></i>Select File</label>
            <input type="file" name="file" class="form-control">
        </div>

        <!-- Button Row -->
        <div class="d-flex justify-content-between">
            <button class="btn btn-primary">
                <i class="bi bi-cloud-upload-fill me-1"></i>Upload File
            </button>

            <!-- Back Button -->
            <a href="{{ url('books') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </form>
</div>


    <!-- Files List -->
    @foreach($files as $file)
        <div class="card file-card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1"><i class="bi bi-file-earmark-text me-1 text-secondary"></i>{{ $file->filename }}</h6>
                    <small class="text-muted">Folder: {{ $file->folder ?: 'Root' }}</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('files.view', $file->id) }}" target="_blank" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('files.download', $file->id) }}" class="btn btn-outline-success btn-sm" download>
                        <i class="bi bi-download"></i>
                    </a>
                    <form action="{{ route('files.delete', $file->id) }}" method="POST" onsubmit="return confirm('Delete this file?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

</div>

    
</body>
</html>
