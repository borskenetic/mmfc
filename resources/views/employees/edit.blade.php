@extends('layouts.admin')

@section('title', 'Edit Employee')

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
            <h1 class="id-title">Edit Faculty</h1>
            <p class="id-subtitle">
                {{ $employee->firstname }} {{ $employee->lastname }}
                @if($employee->employee_id)
                    · ID <code class="id-qr">{{ $employee->employee_id }}</code>
                @endif
                @if($employee->qrcode)
                    · QR <code class="id-qr">{{ $employee->qrcode }}</code>
                @endif
            </p>
        </div>
        <a href="{{ route('employees.index') }}" class="id-btn id-btn-ghost">← Back to Faculty</a>
    </div>

    <form id="employeeForm" method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data" class="id-form-card">
        @csrf
        @method('PUT')

        <div class="id-form-layout">
            <div class="id-form-main">
                <section class="id-form-section">
                    <h2 class="id-form-section-title">Personal Information</h2>
                    <div class="id-form-grid">
                        <div class="id-field">
                            <label for="firstname">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="id-input" value="{{ old('firstname', $employee->firstname) }}" required>
                        </div>
                        <div class="id-field">
                            <label for="lastname">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="id-input" value="{{ old('lastname', $employee->lastname) }}" required>
                        </div>
                        <div class="id-field">
                            <label for="birth_date">Birth Date</label>
                            <input type="date" id="birth_date" name="birth_date" class="id-input" value="{{ old('birth_date', $employee->birth_date) }}" required>
                        </div>
                        <div class="id-field">
                            <label for="sex">Sex</label>
                            <select id="sex" name="sex" class="id-input" required>
                                <option value="">Select Sex</option>
                                @foreach(['MALE' => 'Male', 'FEMALE' => 'Female', 'OTHER' => 'Other'] as $val => $label)
                                    <option value="{{ $val }}" {{ strtoupper(old('sex', $employee->sex ?? '')) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="id-field">
                            <label for="blood_type">Blood Type</label>
                            <input type="text" id="blood_type" name="blood_type" class="id-input" placeholder="e.g. O+" value="{{ old('blood_type', $employee->blood_type) }}">
                        </div>
                        <div class="id-field">
                            <label for="civil_status">Civil Status</label>
                            <input type="text" id="civil_status" name="civil_status" class="id-input" placeholder="e.g. Married" value="{{ old('civil_status', $employee->civil_status) }}">
                        </div>
                    </div>
                </section>

                <section class="id-form-section">
                    <h2 class="id-form-section-title">Employment Information</h2>
                    <div class="id-form-grid">
                        <div class="id-field">
                            <label for="employee_id">Employee ID</label>
                            <input type="text" id="employee_id" name="employee_id" class="id-input" value="{{ old('employee_id', $employee->employee_id) }}" required>
                        </div>
                        <div class="id-field">
                            <label for="department">Department</label>
                            <input type="text" id="department" name="department" class="id-input" value="{{ old('department', $employee->department) }}" required>
                        </div>
                        <div class="id-field">
                            <label for="position">Position</label>
                            <input type="text" id="position" name="position" class="id-input" value="{{ old('position', $employee->position) }}" required>
                        </div>
                        <div class="id-field">
                            <label for="status">Employment Type</label>
                            <select id="status" name="status" class="id-input" required>
                                <option value="">Select Employment Type</option>
                                <option value="FULL TIME" {{ old('status', $employee->status) == 'FULL TIME' ? 'selected' : '' }}>Full Time</option>
                                <option value="PART TIME" {{ old('status', $employee->status) == 'PART TIME' ? 'selected' : '' }}>Part Time</option>
                            </select>
                        </div>
                    </div>
                </section>

                <section class="id-form-section">
                    <h2 class="id-form-section-title">Government IDs</h2>
                    <p class="id-form-section-desc">Numbers only — do not enter N/A or letters.</p>
                    <div class="id-form-grid">
                        <div class="id-field">
                            <label for="tin_id_number">TIN ID Number</label>
                            <input type="text" id="tin_id_number" name="tin_id_number" class="id-input numeric-only" inputmode="numeric" value="{{ old('tin_id_number', $employee->tin_id_number) }}">
                        </div>
                        <div class="id-field">
                            <label for="philhealth_number">PhilHealth Number</label>
                            <input type="text" id="philhealth_number" name="philhealth_number" class="id-input numeric-only" inputmode="numeric" value="{{ old('philhealth_number', $employee->philhealth_number) }}">
                        </div>
                        <div class="id-field">
                            <label for="sss_number">SSS Number</label>
                            <input type="text" id="sss_number" name="sss_number" class="id-input numeric-only" inputmode="numeric" value="{{ old('sss_number', $employee->sss_number) }}">
                        </div>
                        <div class="id-field">
                            <label for="hdmf_number">Pag-IBIG (HDMF) Number</label>
                            <input type="text" id="hdmf_number" name="hdmf_number" class="id-input numeric-only" inputmode="numeric" value="{{ old('hdmf_number', $employee->hdmf_number) }}">
                        </div>
                    </div>
                </section>

                <section class="id-form-section">
                    <h2 class="id-form-section-title">Emergency Contact & Address</h2>
                    <div class="id-form-grid">
                        <div class="id-field">
                            <label for="emergency_contact_name">Contact Name</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" class="id-input" value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}" required>
                        </div>
                        <div class="id-field">
                            <label for="emergency_contact_relationship">Relationship</label>
                            <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" class="id-input" value="{{ old('emergency_contact_relationship', $employee->emergency_contact_relationship) }}" required>
                        </div>
                        <div class="id-field">
                            <label for="emergency_contact_number">Contact Number</label>
                            <input type="text" id="emergency_contact_number" name="emergency_contact_number" class="id-input" value="{{ old('emergency_contact_number', $employee->emergency_contact_number) }}" required>
                        </div>
                        <div class="id-field id-field-full">
                            <label for="address">Home Address</label>
                            <textarea id="address" name="address" class="id-input id-textarea" rows="3" placeholder="Home Address">{{ old('address', $employee->address) }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="id-form-section">
                    <h2 class="id-form-section-title">Signature</h2>
                    <p class="id-form-section-desc">Draw a new signature below to replace the current one.</p>
                    <div class="id-signature-wrap">
                        <canvas id="employeeSignaturePad" class="id-signature-canvas"></canvas>
                        <input type="hidden" name="employee_signature" id="employeeSignatureInput" value="{{ old('employee_signature', $employee->employee_signature) }}">
                        <button type="button" id="clearEmployeeSignature" class="id-btn id-btn-sm id-btn-outline">Clear Signature</button>
                    </div>
                    @if($employee->employee_signature)
                        <div class="id-current-preview">
                            <span class="id-preview-label">Current Signature</span>
                            <img src="{{ asset($employee->employee_signature) }}" alt="Current signature" class="id-signature-preview">
                        </div>
                    @endif
                </section>
            </div>

            <aside class="id-form-sidebar">
                <div class="id-sidebar-card">
                    <h3 class="id-sidebar-title">Formal Picture</h3>
                    <div class="id-photo-preview">
                        @if($employee->formal_picture)
                            <img src="{{ asset($employee->formal_picture) }}" alt="Formal picture" id="profilePreview">
                        @else
                            <img src="{{ asset('images/2x2_undifined_gender.jpg') }}" alt="Default profile" id="profilePreview">
                        @endif
                    </div>
                    <div class="id-field">
                        <label for="formal_picture">Upload New Photo</label>
                        <input type="file" id="formal_picture" name="formal_picture" class="id-input id-file-input" accept=".jpg,.jpeg,.png">
                        <small class="id-hint">Formal 1x1 photo for ID card.</small>
                    </div>
                    @if($employee->formal_picture)
                        <a href="{{ asset($employee->formal_picture) }}" download="formal_{{ $employee->lastname }}.jpg" class="id-btn id-btn-sm id-btn-outline id-btn-block">
                            Download Photo
                        </a>
                    @endif
                </div>
            </aside>
        </div>

        <div class="id-form-footer">
            <a href="{{ route('employees.index') }}" class="id-btn id-btn-ghost">Cancel</a>
            <button type="submit" class="id-btn id-btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    const canvas = document.getElementById('employeeSignaturePad');
    const signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgb(255, 255, 255)' });
    const input = document.getElementById('employeeSignatureInput');

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

    document.getElementById('clearEmployeeSignature').addEventListener('click', () => {
        signaturePad.clear();
        input.value = '';
    });

    document.getElementById('employeeForm').addEventListener('submit', function () {
        if (!signaturePad.isEmpty()) {
            input.value = signaturePad.toDataURL();
        }
    });

    document.getElementById('formal_picture')?.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
            document.getElementById('profilePreview').src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });

    document.querySelectorAll('.numeric-only').forEach(el => {
        el.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
@endpush
