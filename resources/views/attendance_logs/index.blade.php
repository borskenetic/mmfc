<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Attendance Logs</title>
    <link rel="stylesheet" href="{{ asset('public/css/attendance_logs/index.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="flex items-center px-4 py-2 flex-wrap bg-white">
        <img src="{{ asset('images/pantasLogo.png') }}" alt="New Logo" class="header-logo-img"
            style="margin-left: 9rem; max-height: 50px; width: auto;" />
        <h1 class="school-name mb-0 ml-2"></h1>

        <div class="flex gap-2 flex-wrap ml-auto">
            <a href="{{ route('book.index') }}" class="btn0 btn-sm">Home</a>



            <div class="attendance_dropdown" hidden>
                <button class="attendance_dropdown-button">Attendance</button>
                <div class="attendance_dropdown-content">
                    <a href="{{ route('attendance.scan') }}">Attendance</a>
                    <a href="{{ route('attendance_logs.index') }}">Attendance-logs</a>

                </div>
            </div>

            <a class="btn2 btn-sm" href="{{ route('attendance.scan') }}">Attendance</a>
            <a class="btn2 btn-sm" href="{{ route('attendance_logs.index') }}">Attendance-logs</a>


            <a href="{{ route('landing') }}" class="btn2 btn-sm" s hidden> OPAC</a>
            <a href="{{ route('prospectus.index') }}" class="btn3 btn-sm" hidden>Prospectus Manager</a>


            <div class="logs_dropdown" hidden>
                <button class="logs_dropdown-button">Logs</button>
                <div class="logs_dropdown-content">
                    <a href="{{ route('logs.index') }}">Logs</a>
                    <a href="{{ route('rfid.scanner') }}">RFID Scanner</a>
                    <a href="{{ route('book.report.download') }}">Download Book Report</a>
                    <a class="btn4 btn-sm" href="{{ route('students.report') }}">ID Generation</a>
                </div>
            </div>

            <a class="btn4 btn-sm" href="{{ route('students.report') }}">ID Generation</a>

            <a href="https://area51lmslibrary.com/user-account/?fbclid=IwY2xjawLvE-xleHRuA2FlbQIxMABicmlkETFHTzhpTjBrRURpVWFFdW9hAR7tC4LGq_N7YomZscUpiyZKJxd0BCy69WYZuj5CxaseF8G5ctGQnauMPJnheg_aem_ZvE4NOhe8ZwtNtoumemmyg"
                class="btn4 btn-sm" target="_blank" rel="noopener noreferrer" hidden>
                51 Learned
            </a>
            <a href="{{ route('files.index') }}" class="btn4 btn-sm" hidden>Repository</a>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn5">Logout</button>
            </form>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8">

        <div class="mb-4 flex gap-3">
            <a href="{{ route('attendance_logs.export.pdf', request()->query()) }}" class="export-btn">
                📄 Export PDF
            </a>
            <a href="{{ route('attendance_logs.export.excel', request()->query()) }}" class="export-btn">
                📊 Export Excel
            </a>
        </div>

        <!-- ✅ Filters: fully dynamic -->
        <div class="mb-6 no-bg p-4">
            <form method="GET" class="flex flex-col md:flex-row flex-wrap gap-4 items-end">

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="border px-3 py-2 w-full">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="border px-3 py-2 w-full">
                </div>

                <!-- Student Name Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Student Name</label>
                    <select name="student_name" class="border px-3 py-2 w-full">
                        <option value="">All Students</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_name')==$student->id ? 'selected' : ''
                            }}>
                            {{ $student->lastname }}, {{ $student->firstname }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Course Code Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Course Code</label>
                    <select name="course_code" class="border px-3 py-2 w-full">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                        <option value="{{ $course }}" {{ request('course_code')==$course ? 'selected' : '' }}>
                            {{ $course }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Year Level Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                    <select name="year_level" class="border px-3 py-2 w-full">
                        <option value="">All Levels</option>
                        @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year_level')==$year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Button -->
                <div>
                    <button type="submit" class="btn-search">
                        🔍 Search
                    </button>
                </div>
            </form>
        </div>

        <!-- ✅ Attendance Logs Table -->
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="w-full text-sm text-left table-auto">
                <thead id="bar" class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">Last Name</th>
                        <th class="px-4 py-2">First Name</th>
                        <th class="px-4 py-2">Course</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Scanned At</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logs as $log)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">
                            {{ $log->student ? $log->student->lastname : 'Unknown' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $log->student ? $log->student->firstname : 'Unknown' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $log->student ? $log->student->course : 'Unknown' }}
                        </td>
                        <td class="px-4 py-2">
                            @php $status = strtolower($log->status); @endphp
                            @if($status === 'in')
                            <span class="in">IN</span>
                            @elseif($status === 'out')
                            <span class="out">OUT</span>
                            @else
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-gray-500 rounded">
                                Unknown
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            {{ $log->scanned_at ? \Carbon\Carbon::parse($log->scanned_at,
                            'UTC')->timezone('Asia/Manila')->format('Y-m-d h:i A') : '—' }}
                        </td>




                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center px-4 py-6 text-gray-500">No attendance records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </div>


</body>

</html>