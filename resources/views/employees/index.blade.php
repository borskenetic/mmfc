@extends('layouts.admin')

@section('title', 'Registered Faculty')

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
            <h1 class="id-title">Registered Faculty</h1>
            <p class="id-subtitle">Manage faculty records and generate ID cards</p>
        </div>

        <div class="id-tabs" role="tablist" aria-label="Registry type">
            <a href="{{ route('students.index') }}" class="id-tab" role="tab" aria-selected="false">Students</a>
            <a href="{{ route('employees.index') }}" class="id-tab active" role="tab" aria-selected="true">Faculty</a>
        </div>
    </div>

    <div class="id-toolbar">
        <form action="{{ route('employees.index') }}" method="GET" class="id-search-form">
            <input type="text" name="search" class="id-search-input" placeholder="Search faculty by name, department, or position…" value="{{ request('search') }}">
            <button type="submit" class="id-btn id-btn-search">Search</button>
        </form>

        <div class="id-toolbar-actions">
            <a href="{{ route('patron.register', ['tab' => 'employee']) }}" class="id-btn id-btn-primary" target="_blank">+ Register Faculty</a>
            <a href="{{ route('pending.index', ['tab' => 'employees']) }}" class="id-btn id-btn-secondary">Pending Registrations</a>
        </div>
    </div>

    <div class="id-table-card">
        <div class="id-table-meta">
            <span>{{ $faculty->total() }} facult{{ $faculty->total() === 1 ? 'y member' : 'y members' }} registered</span>
        </div>

        <div class="id-table-wrap">
            <table class="id-table">
                <thead>
                    <tr>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>QR Code</th>
                        <th>Actions</th>
                        <th>Generate ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faculty as $employee)
                        <tr>
                            <td>
                                @if($employee->formal_picture)
                                    <img src="{{ asset($employee->formal_picture) }}" alt="Profile" class="id-profile-img">
                                @else
                                    <span class="id-no-image">No Image</span>
                                @endif
                            </td>
                            <td>{{ $employee->firstname }} {{ $employee->lastname }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>{{ $employee->position }}</td>
                            <td><code class="id-qr">{{ $employee->qrcode }}</code></td>
                            <td>
                                <div class="dropdown">
                                    <button class="id-btn id-btn-sm id-btn-outline dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Options
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('employees.edit', $employee->id) }}">Edit</a></li>
                                        <li>
                                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
                                        <li><a class="dropdown-item" href="{{ route('employees.id.front', $employee->id) }}" target="_blank">Front</a></li>
                                        <li><a class="dropdown-item" href="{{ route('employees.id.back', $employee->id) }}" target="_blank">Back</a></li>
                                        <li><a class="dropdown-item" href="{{ route('employees.id.download', $employee->id) }}">Download ZIP</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="id-empty">No faculty found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($faculty->hasPages())
            <div class="id-pagination">
                {{ $faculty->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <div class="id-page-footer">
        <a href="{{ route('book.index') }}" class="id-btn id-btn-ghost">← Back to Home</a>
    </div>
</div>
@endsection
