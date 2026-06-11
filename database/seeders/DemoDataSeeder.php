<?php

namespace Database\Seeders;

use App\Models\AttendanceLog;
use App\Models\Book;
use App\Models\BookLog;
use App\Models\Ebook;
use App\Models\Employee;
use App\Models\PendingEmployee;
use App\Models\PendingStudent;
use App\Models\Program;
use App\Models\ProgramCourse;
use App\Models\ProgramYear;
use App\Models\Prospectus;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $studentRoleId = 1;
        $facultyRoleId = 2;

        $courses = ['BSN', 'BSIT', 'BSED', 'BSBA'];
        $years = ['1ST YEAR', '2ND YEAR', '3RD YEAR', '4TH YEAR'];

        foreach ($courses as $course) {
            foreach ($years as $year) {
                Prospectus::create([
                    'course' => $course,
                    'year' => $year,
                    'subject' => 'General Education',
                ]);
            }
        }

        $bsn = Program::create([
            'program_code' => 'BSN',
            'program_name' => 'Bachelor of Science in Nursing',
            'total_years' => 4,
        ]);

        $bsit = Program::create([
            'program_code' => 'BSIT',
            'program_name' => 'Bachelor of Science in Information Technology',
            'total_years' => 4,
        ]);

        $bsnYear1 = ProgramYear::create(['program_id' => $bsn->id, 'year_level' => 1]);
        $bsitYear1 = ProgramYear::create(['program_id' => $bsit->id, 'year_level' => 1]);

        $anatomy = ProgramCourse::create([
            'program_year_id' => $bsnYear1->id,
            'course_code' => 'NUR101',
            'course_name' => 'Anatomy and Physiology',
        ]);

        $programming = ProgramCourse::create([
            'program_year_id' => $bsitYear1->id,
            'course_code' => 'IT101',
            'course_name' => 'Introduction to Programming',
        ]);

        Ebook::create([
            'title' => 'Fundamentals of Nursing',
            'author' => 'Taylor, Lillis, Lynn',
            'publication_year' => '2022',
            'publisher' => 'Wolters Kluwer',
            'source' => 'Open Library',
            'link' => 'https://example.com/nursing',
            'program_id' => $bsn->id,
            'course_id' => $anatomy->id,
        ]);

        Ebook::create([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'publication_year' => '2008',
            'publisher' => 'Prentice Hall',
            'source' => 'Safari Books',
            'link' => 'https://example.com/clean-code',
            'program_id' => $bsit->id,
            'course_id' => $programming->id,
        ]);

        $students = [
            ['firstname' => 'JUAN D.', 'lastname' => 'DELA CRUZ', 'course' => 'BSN', 'year' => '3RD YEAR', 'qrcode' => '00000001', 'student_id' => '2022-0001'],
            ['firstname' => 'MARIA S.', 'lastname' => 'REYES', 'course' => 'BSIT', 'year' => '2ND YEAR', 'qrcode' => '00000002', 'student_id' => '2023-0012'],
            ['firstname' => 'JOSE M.', 'lastname' => 'GARCIA', 'course' => 'BSED', 'year' => '4TH YEAR', 'qrcode' => '00000003', 'student_id' => '2021-0045'],
            ['firstname' => 'ANA L.', 'lastname' => 'TORRES', 'course' => 'BSBA', 'year' => '1ST YEAR', 'qrcode' => '00000004', 'student_id' => '2024-0088'],
            ['firstname' => 'MARK ANGELO', 'lastname' => 'FLORES', 'course' => 'BSN', 'year' => '2ND YEAR', 'qrcode' => '00000005', 'student_id' => '2023-0033'],
            ['firstname' => 'CIARA B.', 'lastname' => 'MENDOZA', 'course' => 'BSIT', 'year' => '3RD YEAR', 'qrcode' => '00000006', 'student_id' => '2022-0077'],
        ];

        foreach ($students as $data) {
            Student::create([
                ...$data,
                'role_id' => $studentRoleId,
                'birth_date' => '2002-05-15',
                'blood_type' => 'O+',
                'emergency_contact_name' => 'Parent Guardian',
                'emergency_contact_relationship' => 'Parent',
                'emergency_contact_number' => '09171234567',
                'emergency_address' => 'Cagayan de Oro City',
            ]);
        }

        $employees = [
            [
                'firstname' => 'ROBERT',
                'lastname' => 'VILLANUEVA',
                'department' => 'College of Nursing',
                'position' => 'Instructor',
                'status' => 'FULL TIME',
                'employee_id' => 'EMP-1001',
                'qrcode' => 'E-00000001',
                'sex' => 'MALE',
                'tin_id_number' => '123456789',
                'philhealth_number' => '123456789012',
                'sss_number' => '1234567890',
                'hdmf_number' => '123456789012',
            ],
            [
                'firstname' => 'ELENA',
                'lastname' => 'RAMOS',
                'department' => 'College of IT',
                'position' => 'Professor',
                'status' => 'FULL TIME',
                'employee_id' => 'EMP-1002',
                'qrcode' => 'E-00000002',
                'sex' => 'FEMALE',
                'tin_id_number' => '987654321',
                'philhealth_number' => '210987654321',
                'sss_number' => '0987654321',
                'hdmf_number' => '210987654321',
            ],
            [
                'firstname' => 'CARLOS',
                'lastname' => 'NAVARRO',
                'department' => 'Library Services',
                'position' => 'Librarian',
                'status' => 'PART TIME',
                'employee_id' => 'EMP-1003',
                'qrcode' => 'E-00000003',
                'sex' => 'MALE',
                'tin_id_number' => '456789123',
                'philhealth_number' => '456789123456',
                'sss_number' => '4567891234',
                'hdmf_number' => '456789123456',
            ],
        ];

        foreach ($employees as $data) {
            Employee::create([
                ...$data,
                'role_id' => $facultyRoleId,
                'birth_date' => '1985-08-20',
                'civil_status' => 'MARRIED',
                'blood_type' => 'A+',
                'emergency_contact_name' => 'Spouse',
                'emergency_contact_relationship' => 'Spouse',
                'emergency_contact_number' => '09189876543',
                'address' => 'Misamis Oriental',
            ]);
        }

        PendingStudent::create([
            'firstname' => 'PATRICK J.',
            'lastname' => 'LIM',
            'course' => 'BSIT',
            'year' => '1ST YEAR',
            'birth_date' => '2005-01-10',
            'blood_type' => 'B+',
            'emergency_contact_name' => 'Mother',
            'emergency_contact_relationship' => 'Parent',
            'emergency_contact_number' => '09175551234',
            'qrcode' => 'S-00000001',
        ]);

        PendingEmployee::create([
            'firstname' => 'DIANA',
            'lastname' => 'CRUZ',
            'department' => 'College of Education',
            'position' => 'Instructor',
            'status' => 'PART TIME',
            'employee_id' => 'EMP-PENDING-01',
            'employee_number' => 'EMP-PENDING-01',
            'birth_date' => '1990-03-22',
            'sex' => 'FEMALE',
            'tin_id_number' => '111222333',
            'philhealth_number' => '111222333444',
            'sss_number' => '1112223334',
            'hdmf_number' => '111222333444',
            'emergency_contact_name' => 'Sibling',
            'emergency_contact_relationship' => 'Sibling',
            'emergency_contact_number' => '09176667788',
            'address' => 'Bukidnon',
            'role_id' => $facultyRoleId,
            'qrcode' => 'E-PENDING-01',
        ]);

        $books = [
            [
                'barcode' => 'BK-000001',
                'rfid' => 'RFID-BOOK-001',
                'title_statement' => 'Medical-Surgical Nursing',
                'main_author' => 'Lewis, Sharon',
                'publisher' => 'Elsevier',
                'pub_year' => '2021',
                'course' => 'BSN',
                'year' => '3RD YEAR',
                'availability' => 'Available',
            ],
            [
                'barcode' => 'BK-000002',
                'rfid' => 'RFID-BOOK-002',
                'title_statement' => 'Database System Concepts',
                'main_author' => 'Silberschatz, Abraham',
                'publisher' => 'McGraw-Hill',
                'pub_year' => '2020',
                'course' => 'BSIT',
                'year' => '2ND YEAR',
                'availability' => 'Available',
            ],
            [
                'barcode' => 'BK-000003',
                'rfid' => 'RFID-BOOK-003',
                'title_statement' => 'Principles of Management',
                'main_author' => 'Robbins, Stephen',
                'publisher' => 'Pearson',
                'pub_year' => '2019',
                'course' => 'BSBA',
                'year' => '2ND YEAR',
                'availability' => 'Borrowed',
            ],
            [
                'barcode' => 'BK-000004',
                'rfid' => 'RFID-BOOK-004',
                'title_statement' => 'Teaching in the Elementary School',
                'main_author' => 'Kauchak, Donald',
                'publisher' => 'Pearson',
                'pub_year' => '2018',
                'course' => 'BSED',
                'year' => '4TH YEAR',
                'availability' => 'Available',
            ],
        ];

        foreach ($books as $bookData) {
            $book = Book::create($bookData);

            $programId = match ($bookData['course']) {
                'BSN' => $bsn->id,
                'BSIT' => $bsit->id,
                default => null,
            };

            if ($programId) {
                $book->programs()->attach($programId);
            }
        }

        BookLog::create([
            'book_id' => 3,
            'patron_name' => 'DELA CRUZ, JUAN D.',
            'status' => 'Checked Out',
            'timestamp' => Carbon::now()->subDays(2),
        ]);

        $studentIds = Student::pluck('id');
        $now = Carbon::now();

        foreach ($studentIds as $index => $studentId) {
            AttendanceLog::create([
                'student_id' => $studentId,
                'status' => 'in',
                'scanned_at' => $now->copy()->subDays($index + 1)->setTime(8, 15),
            ]);

            AttendanceLog::create([
                'student_id' => $studentId,
                'status' => 'out',
                'scanned_at' => $now->copy()->subDays($index + 1)->setTime(17, 30),
            ]);
        }

        AttendanceLog::create([
            'student_id' => 1,
            'status' => 'in',
            'scanned_at' => $now->copy()->setTime(7, 55),
        ]);
    }
}
