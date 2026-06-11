<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceLog;
use App\Models\Student;
use App\Models\Book;
use App\Models\BookLog;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showScanner()
    {
        return view('attendance.scan');
    }

    public function scan(Request $request)
    {
        $request->validate(['qrcode' => 'required|string']);
        $rfid = $request->qrcode;

        // ✅ Try student first
        $student = Student::where('qrcode', $rfid)->first();
        if ($student) {
            $lastLog = AttendanceLog::where('student_id', $student->id)->latest()->first();
            $newStatus = $lastLog && $lastLog->status === 'IN' ? 'OUT' : 'IN';

            $log = AttendanceLog::create([
                'student_id' => $student->id,
                'status' => $newStatus,
                'scanned_at' => Carbon::now(),
            ]);

            return view('attendance.scan', [
                'student' => $student,
                'status' => $newStatus,
                'log' => $log,
            ]);
        }

        // ✅ Try book next
        $book = Book::where('rfid', $rfid)->first();
        if ($book) {
            $lastLog = BookLog::where('book_id', $book->id)->latest()->first();
            $bookStatus = (!$lastLog || $lastLog->status === 'Checked In')
                ? 'NOT CHECKED OUT'
                : 'CHECKED OUT';

            return view('attendance.scan', [
                'book' => $book,
                'bookStatus' => $bookStatus,
            ]);
        }

        // ❌ Neither student nor book
        return view('attendance.scan')->with('error', 'RFID not recognized.');
    }
}
