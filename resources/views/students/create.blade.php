@extends('layouts.admin')

@section('title', 'Register Student')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/students/create.css') }}">
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-lg">
            <div class="card-header text-center">
                <h4 class="mb-0">Register Patron (Admin)</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="lastname" class="form-control" value="{{ old('lastname') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="firstname" class="form-control" value="{{ old('firstname') }}" required>
                            <small class="text-muted">Format: FIRST NAME & MIDDLE INITIAL (e.g. JUAN D.)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Student ID</label>
                            <input type="text" name="student_id" class="form-control" value="{{ old('student_id') }}">
                            <small class="text-danger">Leave blank if unknown. Do not enter N/A.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Birth Date</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Blood Type</label>
                            <input type="text" name="blood_type" class="form-control" value="{{ old('blood_type') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Course</label>
                            <input type="text" name="course" class="form-control" placeholder="e.g. BSIT / Bachelor of Science in Nursing" value="{{ old('course') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-select" required>
                                <option value="">Select Year</option>
                                @foreach(['1ST','2ND','3RD','4TH','5TH'] as $y)
                                    @php $yearVal = $y . ' YEAR'; @endphp
                                    <option value="{{ $yearVal }}" {{ old('year') == $yearVal ? 'selected' : '' }}>{{ $yearVal }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role_id" class="form-select">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $roles->firstWhere('description', 'student')?->id) == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst($role->description) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact Name</label>
                            <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Relationship</label>
                            <input type="text" name="emergency_contact_relationship" class="form-control" value="{{ old('emergency_contact_relationship') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="emergency_contact_number" class="form-control" value="{{ old('emergency_contact_number') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emergency Address</label>
                            <input type="text" name="emergency_address" class="form-control" value="{{ old('emergency_address') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control" accept=".jpg,.jpeg,.png">
                            <small class="text-muted">1x1 ID picture with plain white background.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Register Patron</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
