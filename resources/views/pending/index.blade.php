@extends('layouts.admin')

@section('title', 'Pending Registrations')

@push('styles')
    <style>.hidden { display: none; }</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header text-center">
        <h4 class="mb-0">Pending Registrations</h4>
    </div>

    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <button id="showStudents" type="button" class="btn btn-primary me-2">View Students</button>
                <button id="showEmployees" type="button" class="btn btn-outline-primary">View Employees</button>
            </div>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">← Back to Registered</a>
        </div>

        <form method="GET" action="{{ route('pending.index') }}" class="mb-3">
            <input type="hidden" name="tab" value="{{ request('tab', 'students') }}">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search...">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <div id="studentTable">
            <h5>Pending Student Registrations</h5>
            <div class="table-responsive">
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
                                        <img src="{{ asset($p->profile_picture) }}" width="80" alt="Profile">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $p->firstname }} {{ $p->lastname }}</td>
                                <td>{{ $p->course }}</td>
                                <td>{{ $p->year }}</td>
                                <td>
                                    <form action="{{ route('students.approve', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="{{ route('students.reject', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No pending student registrations</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $pendingStudents->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <div id="employeeTable" class="hidden">
            <h5>Pending Employee Registrations</h5>
            <div class="table-responsive">
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
                                        <img src="{{ asset($e->formal_picture) }}" width="80" alt="Profile">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $e->firstname }} {{ $e->lastname }}</td>
                                <td>{{ $e->department }}</td>
                                <td>{{ $e->position }}</td>
                                <td>{{ $e->status }}</td>
                                <td>
                                    <form action="{{ route('employees.approve', $e->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="{{ route('employees.reject', $e->id) }}" method="POST" class="d-inline">
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
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $pendingEmployees->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const studentTable = document.getElementById('studentTable');
    const employeeTable = document.getElementById('employeeTable');
    const btnStudents = document.getElementById('showStudents');
    const btnEmployees = document.getElementById('showEmployees');
    const tabInput = document.querySelector('input[name="tab"]');

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

    const tab = new URLSearchParams(window.location.search).get('tab') || 'students';
    if (tab === 'employees') {
        btnEmployees.click();
    } else {
        btnStudents.click();
    }
</script>
@endpush
