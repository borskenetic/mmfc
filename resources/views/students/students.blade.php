@extends('layouts.admin')

@section('title', 'Registered Students')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/students/students.css') }}">
@endpush

@section('content')
<div class="card">
    <div id="container" class="card-header text-center">
        <h4 class="mb-0">Registered Students</h4>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <div class="d-flex mb-2" style="max-width: 350px;">
                <form action="{{ route('students.index') }}" method="GET" class="d-flex w-100">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search patrons..." value="{{ request('search') }}">
                    <button type="submit" id="search" class="btn btn-primary btn-sm ms-2">Search</button>
                </form>
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                @can('isAdmin')
                    <a href="{{ route('students.create') }}" class="btn btn-add">+ Register Patron</a>
                @endcan
                <a href="{{ route('pending.index') }}" class="btn btn-warning">View Pending Registrations</a>
            </div>
        </div>

        <div class="mb-3 text-center">
            <a href="{{ route('students.index') }}" class="btn btn-outline-primary btn-sm active">Students</a>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-primary btn-sm">Faculty</a>
        </div>

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
                            <td>{{ $student->role ? ucfirst($student->role->description) : '—' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Options
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('students.edit', $student->id) }}">Edit</a></li>
                                        <li>
                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
@endsection
