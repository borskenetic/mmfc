<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Library Registration — MMFC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pending/register.css') }}">
</head>
<body class="reg-body">

<div class="reg-page">
    <header class="reg-header">
        <img src="{{ asset('images/pantasLogo.png') }}" alt="Pantas" class="reg-logo">
        <p class="reg-eyebrow">MMFC Library</p>
        <h1 class="reg-title">Online Registration</h1>
        <p class="reg-subtitle">Submit your details for student or employee library ID processing.</p>
    </header>

    <div class="reg-card">
        <div class="reg-card-top">
            <p>Fill out the form below. Your application will be reviewed by library staff.</p>
        </div>

        <div class="reg-card-body">
            @if(session('success'))
                <div class="reg-alert reg-alert-success">{{ session('success') }}</div>
            @endif

            @include('partials.validation-errors')

            @php
                $openEmployeeTab = request('tab') === 'employee'
                    || $errors->has('department')
                    || $errors->has('employee_id')
                    || $errors->has('tin_id_number');
            @endphp

            <div class="reg-tabs" role="tablist">
                <button type="button" class="reg-tab active" id="btnStudent" role="tab" aria-selected="true">Student</button>
                <button type="button" class="reg-tab" id="btnEmployee" role="tab" aria-selected="false">Employee</button>
            </div>

            {{-- STUDENT FORM --}}
            <form id="studentForm" class="reg-form" method="POST" action="{{ route('pending.store') }}" enctype="multipart/form-data">
                @csrf

                <section class="reg-section">
                    <h2 class="reg-section-title">Personal Information</h2>
                    <div class="reg-grid">
                        <div class="reg-field">
                            <label for="student_firstname">First Name</label>
                            <input type="text" id="student_firstname" name="firstname" class="reg-input" placeholder="JUAN D." value="{{ old('firstname') }}" required>
                            <span class="reg-hint">Format: <strong>FIRST NAME & MIDDLE INITIAL</strong> (e.g. JUAN D.)</span>
                        </div>
                        <div class="reg-field">
                            <label for="student_lastname">Last Name</label>
                            <input type="text" id="student_lastname" name="lastname" class="reg-input" placeholder="DELA CRUZ" value="{{ old('lastname') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="student_id">Student ID</label>
                            <input type="text" id="student_id" name="student_id" class="reg-input" placeholder="Optional" value="{{ old('student_id') }}">
                            <span class="reg-hint reg-hint-warn">Leave blank if unknown. Do not enter N/A.</span>
                        </div>
                        <div class="reg-field">
                            <label for="student_birth_date">Birth Date</label>
                            <input type="date" id="student_birth_date" name="birth_date" class="reg-input" value="{{ old('birth_date') }}" required>
                            <span class="reg-hint reg-hint-warn">Use your actual birthdate — not today's date.</span>
                        </div>
                        <div class="reg-field">
                            <label for="student_blood_type">Blood Type</label>
                            <input type="text" id="student_blood_type" name="blood_type" class="reg-input" placeholder="e.g. O+" value="{{ old('blood_type') }}">
                        </div>
                        <div class="reg-field">
                            <label for="student_course">Course</label>
                            <input type="text" id="student_course" name="course" class="reg-input" placeholder="e.g. BSIT" value="{{ old('course') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="student_year">Year Level</label>
                            <select id="student_year" name="year" class="reg-input" required>
                                <option value="">Select year</option>
                                @foreach(['1ST','2ND','3RD','4TH','5TH'] as $y)
                                    <option value="{{ $y }} YEAR" @selected(old('year') === $y . ' YEAR')>{{ $y }} YEAR</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                <section class="reg-section">
                    <h2 class="reg-section-title">Emergency Contact</h2>
                    <div class="reg-grid">
                        <div class="reg-field">
                            <label for="student_emergency_name">Contact Name</label>
                            <input type="text" id="student_emergency_name" name="emergency_contact_name" class="reg-input" value="{{ old('emergency_contact_name') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="student_emergency_relationship">Relationship</label>
                            <input type="text" id="student_emergency_relationship" name="emergency_contact_relationship" class="reg-input" value="{{ old('emergency_contact_relationship') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="student_emergency_number">Contact Number</label>
                            <input type="text" id="student_emergency_number" name="emergency_contact_number" class="reg-input" value="{{ old('emergency_contact_number') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="student_emergency_address">Address</label>
                            <input type="text" id="student_emergency_address" name="emergency_address" class="reg-input" value="{{ old('emergency_address') }}">
                        </div>
                    </div>
                </section>

                <section class="reg-section">
                    <h2 class="reg-section-title">Photo & Signature</h2>
                    <div class="reg-grid">
                        <div class="reg-field reg-field-full">
                            <label for="student_profile_picture">Profile Picture</label>
                            <div class="reg-notice">Upload a <strong>1×1 ID picture</strong> with a <strong>plain white background</strong>.</div>
                            <input type="file" id="student_profile_picture" name="profile_picture" class="reg-input" accept=".jpg,.jpeg,.png">
                        </div>
                        <div class="reg-field reg-field-full">
                            <label>Signature</label>
                            <div class="reg-signature-wrap">
                                <canvas id="studentSignaturePad" class="reg-signature-canvas" aria-label="Draw your signature"></canvas>
                                <input type="hidden" name="student_signature" id="studentSignatureInput">
                                <button type="button" id="clearStudentSignature" class="reg-btn-clear">Clear signature</button>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="reg-submit">
                    <button type="submit" class="reg-btn-submit">Submit Student Registration</button>
                </div>
            </form>

            {{-- EMPLOYEE FORM --}}
            <form id="employeeForm" class="reg-form hidden" method="POST" action="{{ route('pendingEmployee.store') }}" enctype="multipart/form-data">
                @csrf

                <section class="reg-section">
                    <h2 class="reg-section-title">Personal Information</h2>
                    <div class="reg-grid">
                        <div class="reg-field">
                            <label for="employee_firstname">First Name</label>
                            <input type="text" id="employee_firstname" name="firstname" class="reg-input" value="{{ old('firstname') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="employee_lastname">Last Name</label>
                            <input type="text" id="employee_lastname" name="lastname" class="reg-input" value="{{ old('lastname') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="employee_department">Department</label>
                            <input type="text" id="employee_department" name="department" class="reg-input" value="{{ old('department') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="employee_position">Position</label>
                            <input type="text" id="employee_position" name="position" class="reg-input" value="{{ old('position') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="employee_status">Employment Type</label>
                            <select id="employee_status" name="status" class="reg-input" required>
                                <option value="">Select type</option>
                                <option value="FULL TIME" @selected(old('status') === 'FULL TIME')>Full Time</option>
                                <option value="PART TIME" @selected(old('status') === 'PART TIME')>Part Time</option>
                            </select>
                        </div>
                        <div class="reg-field">
                            <label for="employee_id">Employee ID</label>
                            <input type="text" id="employee_id" name="employee_id" class="reg-input" value="{{ old('employee_id') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="employee_birth_date">Birth Date</label>
                            <input type="date" id="employee_birth_date" name="birth_date" class="reg-input" value="{{ old('birth_date') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="employee_sex">Sex</label>
                            <select id="employee_sex" name="sex" class="reg-input" required>
                                <option value="">Select sex</option>
                                <option value="MALE" @selected(old('sex') === 'MALE')>Male</option>
                                <option value="FEMALE" @selected(old('sex') === 'FEMALE')>Female</option>
                                <option value="OTHER" @selected(old('sex') === 'OTHER')>Other</option>
                            </select>
                        </div>
                        <div class="reg-field">
                            <label for="employee_blood_type">Blood Type</label>
                            <input type="text" id="employee_blood_type" name="blood_type" class="reg-input" value="{{ old('blood_type') }}">
                        </div>
                        <div class="reg-field">
                            <label for="employee_civil_status">Civil Status</label>
                            <input type="text" id="employee_civil_status" name="civil_status" class="reg-input" value="{{ old('civil_status') }}">
                        </div>
                    </div>
                </section>

                <section class="reg-section">
                    <h2 class="reg-section-title">Government IDs</h2>
                    <div class="reg-grid">
                        <div class="reg-field">
                            <label for="employee_tin">TIN ID Number</label>
                            <input type="text" id="employee_tin" name="tin_id_number" class="reg-input numeric-only" inputmode="numeric" pattern="[0-9]+" value="{{ old('tin_id_number') }}" required>
                            <span class="reg-hint reg-hint-warn">Required. Numbers only.</span>
                        </div>
                        <div class="reg-field">
                            <label for="employee_philhealth">PhilHealth Number</label>
                            <input type="text" id="employee_philhealth" name="philhealth_number" class="reg-input numeric-only" inputmode="numeric" pattern="[0-9]+" value="{{ old('philhealth_number') }}" required>
                            <span class="reg-hint reg-hint-warn">Required. Numbers only.</span>
                        </div>
                        <div class="reg-field">
                            <label for="employee_sss">SSS Number</label>
                            <input type="text" id="employee_sss" name="sss_number" class="reg-input numeric-only" inputmode="numeric" pattern="[0-9]+" value="{{ old('sss_number') }}" required>
                            <span class="reg-hint reg-hint-warn">Required. Numbers only.</span>
                        </div>
                        <div class="reg-field">
                            <label for="employee_hdmf">Pag-IBIG (HDMF) Number</label>
                            <input type="text" id="employee_hdmf" name="hdmf_number" class="reg-input numeric-only" inputmode="numeric" pattern="[0-9]+" value="{{ old('hdmf_number') }}" required>
                            <span class="reg-hint reg-hint-warn">Required. Numbers only.</span>
                        </div>
                    </div>
                </section>

                <section class="reg-section">
                    <h2 class="reg-section-title">Emergency Contact & Address</h2>
                    <div class="reg-grid">
                        <div class="reg-field">
                            <label for="employee_emergency_name">Contact Name</label>
                            <input type="text" id="employee_emergency_name" name="emergency_contact_name" class="reg-input" value="{{ old('emergency_contact_name') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="employee_emergency_relationship">Relationship</label>
                            <input type="text" id="employee_emergency_relationship" name="emergency_contact_relationship" class="reg-input" value="{{ old('emergency_contact_relationship') }}" required>
                        </div>
                        <div class="reg-field">
                            <label for="employee_emergency_number">Contact Number</label>
                            <input type="text" id="employee_emergency_number" name="emergency_contact_number" class="reg-input" value="{{ old('emergency_contact_number') }}" required>
                        </div>
                        <div class="reg-field reg-field-full">
                            <label for="employee_address">Home Address</label>
                            <textarea id="employee_address" name="address" class="reg-input reg-textarea" rows="2">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="reg-section">
                    <h2 class="reg-section-title">Photo & Signature</h2>
                    <div class="reg-grid">
                        <div class="reg-field reg-field-full">
                            <label for="employee_formal_picture">Formal Picture</label>
                            <input type="file" id="employee_formal_picture" name="formal_picture" class="reg-input" accept=".jpg,.jpeg,.png">
                        </div>
                        <div class="reg-field reg-field-full">
                            <label>Signature</label>
                            <div class="reg-signature-wrap">
                                <canvas id="employeeSignaturePad" class="reg-signature-canvas" aria-label="Draw your signature"></canvas>
                                <input type="hidden" name="employee_signature" id="employeeSignatureInput">
                                <button type="button" id="clearEmployeeSignature" class="reg-btn-clear">Clear signature</button>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="reg-submit">
                    <button type="submit" class="reg-btn-submit employee">Submit Employee Registration</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="reg-footer">
        Powered by Pantas &middot; <a href="{{ route('book.index') }}">Back to library home</a>
    </footer>
</div>

<script>
function setupSignaturePad(canvasId, inputId, clearBtnId) {
    const canvas = document.getElementById(canvasId);
    const ctx = canvas.getContext('2d');
    let drawing = false;
    let points = [];

    function resizeCanvas() {
        const dataUrl = canvas.toDataURL();
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
        const img = new Image();
        img.src = dataUrl;
        img.onload = () => ctx.drawImage(img, 0, 0);
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: (clientX - rect.left) * scaleX,
            y: (clientY - rect.top) * scaleY
        };
    }

    function startDrawing(e) {
        e.preventDefault();
        drawing = true;
        points = [];
        points.push(getPos(e));
    }

    function draw(e) {
        e.preventDefault();
        if (!drawing) return;

        const pos = getPos(e);
        points.push(pos);

        ctx.lineCap = 'round';
        ctx.strokeStyle = '#1a1220';

        if (points.length === 1) {
            ctx.beginPath();
            ctx.arc(pos.x, pos.y, 1.5, 0, Math.PI * 2);
            ctx.fill();
            return;
        }

        ctx.beginPath();
        const last = points[points.length - 2];
        const dx = pos.x - last.x;
        const dy = pos.y - last.y;
        const speed = Math.sqrt(dx * dx + dy * dy);
        ctx.lineWidth = Math.max(1, 4 - speed / 2);
        ctx.moveTo(last.x, last.y);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }

    function stopDrawing() {
        if (!drawing) return;
        drawing = false;
        document.getElementById(inputId).value = canvas.toDataURL();
    }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);
    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDrawing);

    document.getElementById(clearBtnId).addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        points = [];
        document.getElementById(inputId).value = '';
    });

    return { resize: resizeCanvas };
}

const studentPad = setupSignaturePad('studentSignaturePad', 'studentSignatureInput', 'clearStudentSignature');
const employeePad = setupSignaturePad('employeeSignaturePad', 'employeeSignatureInput', 'clearEmployeeSignature');

const btnStudent = document.getElementById('btnStudent');
const btnEmployee = document.getElementById('btnEmployee');
const studentForm = document.getElementById('studentForm');
const employeeForm = document.getElementById('employeeForm');

function showStudentTab() {
    studentForm.classList.remove('hidden');
    employeeForm.classList.add('hidden');
    btnStudent.classList.add('active');
    btnEmployee.classList.remove('active');
    btnStudent.setAttribute('aria-selected', 'true');
    btnEmployee.setAttribute('aria-selected', 'false');
    setTimeout(() => studentPad.resize(), 50);
}

function showEmployeeTab() {
    employeeForm.classList.remove('hidden');
    studentForm.classList.add('hidden');
    btnEmployee.classList.add('active');
    btnStudent.classList.remove('active');
    btnEmployee.setAttribute('aria-selected', 'true');
    btnStudent.setAttribute('aria-selected', 'false');
    setTimeout(() => employeePad.resize(), 50);
}

btnStudent.addEventListener('click', showStudentTab);
btnEmployee.addEventListener('click', showEmployeeTab);

@if($openEmployeeTab)
showEmployeeTab();
@endif

document.querySelectorAll('#employeeForm .numeric-only').forEach(input => {
    input.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>

</body>
</html>
