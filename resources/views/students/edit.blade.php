@extends('layouts.admin')

@section('title', 'Edit Student')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/students/id_registry.css') }}">
@endpush

@section('content')
<div class="id-page">
    <div class="id-page-header">
        <div class="id-page-intro">
            <p class="id-eyebrow">ID Generation</p>
            <h1 class="id-title">Edit Student</h1>
            <p class="id-subtitle">
                {{ $student->firstname }} {{ $student->lastname }}
                @if($student->qrcode)
                    · QR <code class="id-qr">{{ $student->qrcode }}</code>
                @endif
            </p>
        </div>
        <a href="{{ route('students.index') }}" class="id-btn id-btn-ghost">← Back to Students</a>
    </div>

    <form id="studentForm" method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data" class="id-form-card">
        @csrf
        @method('PUT')

        <div class="id-form-layout">
            <div class="id-form-main">
                <section class="id-form-section">
                    <h2 class="id-form-section-title">Personal Information</h2>
                    <div class="id-form-grid">
                        <div class="id-field">
                            <label for="firstname">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="id-input" value="{{ old('firstname', $student->firstname) }}" required>
                            <small class="id-hint">Format: FIRST NAME & MIDDLE INITIAL (e.g. JUAN D.)</small>
                        </div>
                        <div class="id-field">
                            <label for="lastname">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="id-input" value="{{ old('lastname', $student->lastname) }}" required>
                        </div>
                        <div class="id-field">
                            <label for="student_id">Student ID</label>
                            <input type="text" id="student_id" name="student_id" class="id-input" value="{{ old('student_id', $student->student_id) }}">
                        </div>
                        <div class="id-field">
                            <label for="birth_date">Birth Date</label>
                            <input type="date" id="birth_date" name="birth_date" class="id-input" value="{{ old('birth_date', $student->birth_date) }}">
                        </div>
                        <div class="id-field">
                            <label for="blood_type">Blood Type</label>
                            <input type="text" id="blood_type" name="blood_type" class="id-input" placeholder="e.g. O+" value="{{ old('blood_type', $student->blood_type) }}">
                        </div>
                    </div>
                </section>

                <section class="id-form-section">
                    <h2 class="id-form-section-title">Academic Information</h2>
                    <div class="id-form-grid">
                        <div class="id-field">
                            <label for="course">Course</label>
                            <input type="text" id="course" name="course" class="id-input" placeholder="e.g. BSIT / Bachelor of Science in Nursing" value="{{ old('course', $student->course) }}">
                        </div>
                        <div class="id-field">
                            <label for="year">Year Level</label>
                            <select id="year" name="year" class="id-input">
                                <option value="">Select Year</option>
                                @foreach(['1ST YEAR','2ND YEAR','3RD YEAR','4TH YEAR','5TH YEAR'] as $yearOption)
                                    <option value="{{ $yearOption }}" {{ old('year', $student->year) == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                                @endforeach
                                @if($student->year && !in_array($student->year, ['1ST YEAR','2ND YEAR','3RD YEAR','4TH YEAR','5TH YEAR']))
                                    <option value="{{ $student->year }}" selected>{{ $student->year }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="id-field">
                            <label for="role_id">Role</label>
                            <select id="role_id" name="role_id" class="id-input">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $student->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst($role->description) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                <section class="id-form-section">
                    <h2 class="id-form-section-title">Emergency Contact</h2>
                    <div class="id-form-grid">
                        <div class="id-field">
                            <label for="emergency_contact_name">Contact Name</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" class="id-input" value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}">
                        </div>
                        <div class="id-field">
                            <label for="emergency_contact_relationship">Relationship</label>
                            <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" class="id-input" value="{{ old('emergency_contact_relationship', $student->emergency_contact_relationship) }}">
                        </div>
                        <div class="id-field">
                            <label for="emergency_contact_number">Contact Number</label>
                            <input type="text" id="emergency_contact_number" name="emergency_contact_number" class="id-input" value="{{ old('emergency_contact_number', $student->emergency_contact_number) }}">
                        </div>
                        <div class="id-field id-field-full">
                            <label for="emergency_address">Address</label>
                            <input type="text" id="emergency_address" name="emergency_address" class="id-input" value="{{ old('emergency_address', $student->emergency_address) }}">
                        </div>
                    </div>
                </section>

                <section class="id-form-section">
                    <h2 class="id-form-section-title">Signature</h2>
                    <p class="id-form-section-desc">Draw a new signature below to replace the current one.</p>
                    <div class="id-signature-wrap">
                        <canvas id="studentSignaturePad" class="id-signature-canvas"></canvas>
                        <input type="hidden" name="student_signature" id="studentSignatureInput" value="{{ old('student_signature', $student->student_signature) }}">
                        <button type="button" id="clearStudentSignature" class="id-btn id-btn-sm id-btn-outline">Clear Signature</button>
                    </div>
                    @if($student->student_signature)
                        <div class="id-current-preview">
                            <span class="id-preview-label">Current Signature</span>
                            <img src="{{ asset($student->student_signature) }}" alt="Current signature" class="id-signature-preview">
                        </div>
                    @endif
                </section>
            </div>

            <aside class="id-form-sidebar">
                <div class="id-sidebar-card">
                    <h3 class="id-sidebar-title">Profile Picture</h3>
                    <div class="id-photo-preview">
                        @if($student->profile_picture)
                            <img src="{{ asset($student->profile_picture) }}" alt="Profile picture" id="profilePreview">
                        @else
                            <img src="{{ asset('images/2x2_undifined_gender.jpg') }}" alt="Default profile" id="profilePreview">
                        @endif
                    </div>
                    <div class="id-field">
                        <label for="profile_picture">Upload New Photo</label>
                        <input type="file" id="profile_picture" name="profile_picture" class="id-input id-file-input" accept=".jpg,.jpeg,.png">
                        <small class="id-hint">1x1 ID picture, plain white background.</small>
                    </div>
                    @if($student->profile_picture)
                        <a href="{{ asset($student->profile_picture) }}" download="profile_{{ $student->lastname }}.jpg" class="id-btn id-btn-sm id-btn-outline id-btn-block">
                            Download Photo
                        </a>
                    @endif
                </div>
            </aside>
        </div>

        <div class="id-form-footer">
            <a href="{{ route('students.index') }}" class="id-btn id-btn-ghost">Cancel</a>
            <button type="submit" class="id-btn id-btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    const canvas = document.getElementById('studentSignaturePad');
    const signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgb(255, 255, 255)' });
    const input = document.getElementById('studentSignatureInput');

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const width = canvas.offsetWidth;
        const height = 160;
        canvas.width = width * ratio;
        canvas.height = height * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        canvas.style.height = height + 'px';
        signaturePad.clear();
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    document.getElementById('clearStudentSignature').addEventListener('click', () => {
        signaturePad.clear();
        input.value = '';
    });

    document.getElementById('studentForm').addEventListener('submit', function () {
        if (!signaturePad.isEmpty()) {
            input.value = signaturePad.toDataURL();
        }
    });

    document.getElementById('profile_picture')?.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
            document.getElementById('profilePreview').src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
