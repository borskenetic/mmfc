<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Prospectus;
use App\Models\Employee;
use App\Models\Role;
use App\Models\PendingStudent;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // Show all students
    public function index(Request $request)
    {
        return $this->getStudentsByRole($request, 'student');
    }

    public function faculty(Request $request)
    {
        return redirect()->route('employees.index', $request->query());
    }
    

    private function getStudentsByRole(Request $request, $roleDescription)
    {
        $query = Student::with('role')
            ->whereHas('role', function ($q) use ($roleDescription) {
                $q->whereRaw('LOWER(description) = ?', [strtolower($roleDescription)]);
            });
    
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('lastname', 'like', "%{$search}%")
                  ->orWhere('firstname', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%")
                  ->orWhere('qrcode', 'like', "%{$search}%");
            });
        }
    
        // 👇 Alphabetical sorting
        $students = $query
            ->orderBy('lastname', 'asc')
            ->orderBy('firstname', 'asc')
            ->paginate(15)            // 👈 PAGINATION
            ->appends(['search' => $request->search]); // keep search on pagination links
    
        return view('students.students', compact('students', 'roleDescription'));
    }



    // Show form to create new student
    public function create()
    {
        $courses = Prospectus::select('course')->distinct()->pluck('course');
        $roles = Role::all();
        return view('students.create', compact('courses', 'roles'));
    }

    // Store new student
   // Store new student
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'student_id' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'blood_type' => 'nullable|string|max:5',
            'course' => 'required|string',
            'year' => 'required|string',
            'role_id' => 'nullable|exists:roles,id',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:255',
            'emergency_address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (empty($validated['role_id'])) {
            $validated['role_id'] = Role::whereRaw('LOWER(description) = ?', ['student'])->value('id') ?? 1;
        }
    
        // ✅ Save profile picture
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(base_path('images/profile_pictures'), $filename);
            $validated['profile_picture'] = 'images/profile_pictures/' . $filename;
        }
    
        // ✅ Generate sequential QR code
        $lastStudent = Student::orderBy('id', 'desc')->first();
        $lastCode = $lastStudent ? intval($lastStudent->qrcode) : 0;
        $newCode = str_pad($lastCode + 1, 8, '0', STR_PAD_LEFT);
        $validated['qrcode'] = $newCode;
    
        Student::create($validated);
    
        return redirect()->route('students.index')->with('success', 'Student Registered Successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $courses = Prospectus::select('course')->distinct()->pluck('course');
        $roles = Role::all();
        return view('students.edit', compact('student', 'courses', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
    
        $validated = $request->validate([
            'student_id' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'blood_type' => 'nullable|string|max:5',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:255',
            'emergency_address' => 'nullable|string|max:255',
            'student_signature' => 'nullable|string',
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'qrcode' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // ✅ Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            if ($student->profile_picture && file_exists(base_path($student->profile_picture))) {
                unlink(base_path($student->profile_picture));
            }
    
            $image = $request->file('profile_picture');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
            $image->move(base_path('images/profile_pictures'), $filename);
            $validated['profile_picture'] = 'images/profile_pictures/' . $filename;
        }
    
        // ✅ Handle signature from base64
        if (!empty($validated['student_signature']) && str_starts_with($validated['student_signature'], 'data:')) {
            [$meta, $contents] = explode(',', $validated['student_signature'], 2);
            $ext = 'png';
            if (preg_match('/data:image\/(jpeg|jpg)/i', $meta)) $ext = 'jpg';
            $sigName = time() . '_sig.' . $ext;
    
            if (!file_exists(base_path('images/signatures'))) {
                mkdir(base_path('images/signatures'), 0755, true);
            }
    
            file_put_contents(base_path('images/signatures/' . $sigName), base64_decode($contents));
            $validated['student_signature'] = 'images/signatures/' . $sigName;
        }
    
        // ✅ Update record
        $student->update($validated);
    
        return redirect()->route('students.index')->with('success', 'Student updated successfully!');
    }



    // Delete student
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        if ($student->profile_picture) {
            Storage::disk('public')->delete($student->profile_picture);
        }

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student Deleted Successfully!');
    }

    // Optional: show single student (not used if you don't need)
    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('students.show', compact('student'));
    }
    
    public function pending()
    {
        return redirect()->route('pending.index');
    }
    
    public function approve($id)
    {
       
        DB::beginTransaction();
    
        try {
            $pending = PendingStudent::findOrFail($id);
    
            // Generate next QR code
            $lastStudent = Student::orderBy('id', 'desc')->first();
            $newQr = str_pad(($lastStudent ? intval($lastStudent->qrcode) + 1 : 1), 8, '0', STR_PAD_LEFT);
    
            // Role ID for student (as per your setup)
            $rid = 1; // change this if your students use a different role_id
    
            // Insert full student record
            $student = Student::create([
                'student_id' => $pending->student_id,
                'firstname'  => $pending->firstname,
                'lastname'   => $pending->lastname,
                'course'     => $pending->course,
                'year'       => $pending->year,
                'birth_date' => $pending->birth_date,
                'blood_type' => $pending->blood_type,
                'profile_picture' => $pending->profile_picture,
                'qrcode' => $newQr,
                'emergency_contact_name' => $pending->emergency_contact_name,
                'emergency_contact_relationship' => $pending->emergency_contact_relationship,
                'emergency_contact_number' => $pending->emergency_contact_number,
                'emergency_address' => $pending->emergency_address,
                'student_signature' => $pending->student_signature,
                'role_id' => $rid,
            ]);
    
            // Delete from pending once approved
            $pending->delete();
    
            DB::commit();
            return back()->with('success', 'Student approved and added to the students table.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    
    public function reject($id)
    {
        $pending = PendingStudent::findOrFail($id);
        $pending->delete();
    
        return back()->with('success', 'Registration rejected.');
    }
    
}
