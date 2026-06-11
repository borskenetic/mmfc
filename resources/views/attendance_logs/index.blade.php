@extends('layouts.admin')

@section('title', 'Attendance Logs')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/attendance_logs/index.css') }}">
@endpush

@section('content')
<div class="logs-page">
    <div class="logs-page-header">
        <div>
            <p class="logs-eyebrow">MMFC Library</p>
            <h1 class="logs-title">Attendance Logs</h1>
            <p class="logs-subtitle">Track patron check-ins and check-outs</p>
        </div>
        <div class="logs-actions">
            <a href="{{ route('attendance_logs.export.pdf', request()->query()) }}" class="logs-btn logs-btn-outline">
                Export PDF
            </a>
            <a href="{{ route('attendance_logs.export.excel', request()->query()) }}" class="logs-btn logs-btn-primary">
                Export Excel
            </a>
        </div>
    </div>

    <div class="logs-filter-card">
        <form method="GET" class="logs-filter-form">
            <div class="filter-field">
                <label for="from">From</label>
                <input type="date" id="from" name="from" value="{{ request('from') }}">
            </div>
            <div class="filter-field">
                <label for="to">To</label>
                <input type="date" id="to" name="to" value="{{ request('to') }}">
            </div>
            <div class="filter-field">
                <label for="student_name">Student</label>
                <select id="student_name" name="student_name">
                    <option value="">All Students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_name') == $student->id ? 'selected' : '' }}>
                            {{ $student->lastname }}, {{ $student->firstname }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-field">
                <label for="course_code">Course</label>
                <select id="course_code" name="course_code">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course }}" {{ request('course_code') == $course ? 'selected' : '' }}>
                            {{ $course }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-field">
                <label for="year_level">Year Level</label>
                <select id="year_level" name="year_level">
                    <option value="">All Levels</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year_level') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-field filter-field-action">
                <label>&nbsp;</label>
                <button type="submit" class="logs-btn logs-btn-search">Search</button>
            </div>
        </form>
    </div>

    <div class="logs-table-card">
        <div class="logs-table-meta">
            <span>{{ $logs->total() }} record{{ $logs->total() === 1 ? '' : 's' }} found</span>
        </div>

        <div class="table-wrap">
            <table class="logs-table">
                <thead>
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Scanned At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->student?->lastname ?? 'Unknown' }}</td>
                            <td>{{ $log->student?->firstname ?? 'Unknown' }}</td>
                            <td>{{ $log->student?->course ?? '—' }}</td>
                            <td>
                                @php $status = strtolower($log->status); @endphp
                                @if($status === 'in')
                                    <span class="status-pill status-in">IN</span>
                                @elseif($status === 'out')
                                    <span class="status-pill status-out">OUT</span>
                                @else
                                    <span class="status-pill status-unknown">Unknown</span>
                                @endif
                            </td>
                            <td class="scanned-at">
                                {{ $log->scanned_at
                                    ? \Carbon\Carbon::parse($log->scanned_at, 'UTC')->timezone('Asia/Manila')->format('M d, Y · h:i A')
                                    : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="logs-pagination">
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
