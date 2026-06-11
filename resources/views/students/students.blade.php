@extends('layouts.admin')

@section('title', 'Registered Students')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/students/students.css') }}">
    <link rel="stylesheet" href="{{ asset('css/students/id_registry.css') }}">
@endpush

@section('content')
<div class="id-page">
    <div class="id-page-header">
        <div class="id-page-intro">
            <p class="id-eyebrow">ID Generation</p>
            <h1 class="id-title">Registered Students</h1>
            <p class="id-subtitle">Manage patron records and generate ID cards</p>
        </div>

        <div class="id-tabs" role="tablist" aria-label="Registry type">
            <a href="{{ route('students.index') }}" class="id-tab active" role="tab" aria-selected="true">Students</a>
            <a href="{{ route('employees.index') }}" class="id-tab" role="tab" aria-selected="false">Faculty</a>
        </div>
    </div>

    <div class="id-toolbar">
        <form action="{{ route('students.index') }}" method="GET" class="id-search-form">
            <input type="text" name="search" class="id-search-input" placeholder="Search patrons by name, course, or QR code…" value="{{ request('search') }}">
            <button type="submit" class="id-btn id-btn-search">Search</button>
        </form>

        <div class="id-toolbar-actions">
            @can('isAdmin')
                <a href="{{ route('students.create') }}" class="id-btn id-btn-primary">+ Register Patron</a>
            @endcan
            <a href="{{ route('pending.index') }}" class="id-btn id-btn-secondary">Pending Registrations</a>
        </div>
    </div>

    <div class="id-table-card">
        <div class="id-table-meta">
            <span>{{ $students->total() }} student{{ $students->total() === 1 ? '' : 's' }} registered</span>
        </div>

        <div class="id-table-wrap">
            <table class="id-table">
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
                                    <img src="{{ asset($student->profile_picture) }}" alt="Profile" class="id-profile-img">
                                @else
                                    <span class="id-no-image">No Image</span>
                                @endif
                            </td>
                            <td>{{ $student->lastname }}</td>
                            <td>{{ $student->firstname }}</td>
                            <td><code class="id-qr">{{ $student->qrcode }}</code></td>
                            <td>{{ $student->course }}</td>
                            <td>{{ $student->year }}</td>
                            <td>{{ $student->role ? ucfirst($student->role->description) : '—' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="id-btn id-btn-sm id-btn-outline dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Options
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('students.edit', $student->id) }}">Edit</a></li>
                                        <li>
                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="id-btn id-btn-sm id-btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
                        <tr>
                            <td colspan="9" class="id-empty">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="id-pagination">
                {{ $students->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <div class="id-page-footer">
        <a href="{{ route('book.index') }}" class="id-btn id-btn-ghost">← Back to Home</a>
    </div>
</div>
@endsection
