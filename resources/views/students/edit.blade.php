<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        canvas {
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn-save {
            background-color: #007bff;
            color: white;
        }
        .btn-save:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="card">
        <div class="card-header text-center">
            <h4>Edit Student Information</h4>
        </div>

        <div class="card-body">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <form id="studentForm" method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h5 class="mb-3">Student Information</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="student_id" class="form-control" placeholder="Student ID" value="{{ old('student_id', $student->student_id) }}" >
                    </div>

                    <div class="col-md-6">
                        <input type="date" name="birth_date" class="form-control" placeholder="Birth Date" value="{{ old('birth_date', $student->birth_date) }}">
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="firstname" class="form-control" placeholder="First Name" value="{{ old('firstname', $student->firstname) }}" >
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="lastname" class="form-control" placeholder="Last Name" value="{{ old('lastname', $student->lastname) }}" >
                    </div>
         
                    <div class="col-md-6">
                        <input type="text" name="course" class="form-control" placeholder="Course" value="{{ old('course', $student->course) }}" >
                    </div>

                    <div class="col-md-6">
                        <select name="year" class="form-select" >
                            <option value="">Select Year</option>
                            <option value="First Year" {{ old('year', $student->year) == 'First Year' ? 'selected' : '' }}>First Year</option>
                            <option value="Second Year" {{ old('year', $student->year) == 'Second Year' ? 'selected' : '' }}>Second Year</option>
                            <option value="Third Year" {{ old('year', $student->year) == 'Third Year' ? 'selected' : '' }}>Third Year</option>
                            <option value="Fourth Year" {{ old('year', $student->year) == 'Fourth Year' ? 'selected' : '' }}>Fourth Year</option>
                            <option value="Fifth Year" {{ old('year', $student->year) == 'Fifth Year' ? 'selected' : '' }}>Fifth Year</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="blood_type" class="form-control" placeholder="Blood Type (e.g., O+)" value="{{ old('blood_type', $student->blood_type) }}">
                    </div>

                    <div class="col-md-6" hidden>
                        <select name="role_id" class="form-select" >
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $student->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="emergency_contact_name" class="form-control" placeholder="Emergency Contact Name" value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}">
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="emergency_contact_relationship" class="form-control" placeholder="Relationship" value="{{ old('emergency_contact_relationship', $student->emergency_contact_relationship) }}">
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="emergency_contact_number" class="form-control" placeholder="Contact Number" value="{{ old('emergency_contact_number', $student->emergency_contact_number) }}">
                    </div>
                    
                    <div class="col-md-6">
                        <input type="text" name="emergency_address" class="form-control" placeholder="Address" value="{{ old('emergency_address', $student->emergency_address) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control" accept=".jpg,.jpeg,.png">
                        @if($student->profile_picture)
                            <div class="mt-2">
                                <img src="{{ asset($student->profile_picture) }}" alt="Profile Picture" width="120" class="rounded">
                            </div>
                        @endif
                    </div>
                    
                    @if($student->profile_picture)
                        <div class="mt-2">
                            <a href="{{ asset($student->profile_picture) }}" 
                               download="profile_{{ $student->lastname }}.jpg"
                               class="btn btn-outline-primary btn-sm mt-2">
                                Download Picture
                            </a>
                        </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label">Signature (draw below)</label><br>
                        <canvas id="studentSignaturePad" width="500" height="150"></canvas>
                        <input type="hidden" name="student_signature" id="studentSignatureInput" value="{{ old('student_signature', $student->student_signature) }}">
                        <div class="mt-2">
                            <button type="button" id="clearStudentSignature" class="btn btn-outline-danger btn-sm">Clear</button>
                        </div>

                        @if($student->student_signature)
                            <div class="mt-3">
                                <p>Current Signature:</p>
                                <img src="{{ asset($student->student_signature) }}" alt="Current Signature" height="80">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-save px-4">Update Student</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary px-4">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const canvas = document.getElementById('studentSignaturePad');
    const signaturePad = new SignaturePad(canvas);
    const input = document.getElementById('studentSignatureInput');

    document.getElementById('clearStudentSignature').addEventListener('click', () => {
        signaturePad.clear();
        input.value = '';
    });

    document.getElementById('studentForm').addEventListener('submit', function () {
        if (!signaturePad.isEmpty()) {
            input.value = signaturePad.toDataURL();
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
