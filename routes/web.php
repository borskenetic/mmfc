<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookLogController;
use App\Http\Controllers\RFIDScanController;
use App\Http\Controllers\BookImportController; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\ProspectusController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\IdCardController;
use App\Http\Controllers\PendingStudentController;
use App\Http\Controllers\PendingEmployeeController;
use App\Http\Controllers\EmployeeIdCardController;
use App\Http\Controllers\EmployeeController;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Carbon\Carbon;
use App\Models\Book;

// =============================
// Public Routes
// =============================
Route::get('/', fn() => redirect()->route('login'));
Route::get('/register', [PendingStudentController::class, 'create'])->name('patron.register');
Route::post('/register', [PendingStudentController::class, 'store'])->name('pending.store');
Route::post('/register-employee', [PendingEmployeeController::class, 'store'])->name('pendingEmployee.store');


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/index', fn() => redirect()->route('attendance_logs.index'));
Route::get('/filter/years', [BookController::class, 'getYears']);
Route::get('/filter/courses', [BookController::class, 'getCourses']);
Route::get('/attendance', [AttendanceController::class, 'showScanner'])->name('attendance.scan');
Route::post('/attendance', [AttendanceController::class, 'scan'])->name('attendance.process');

// =============================
// Student / Faculty only
// =============================
Route::middleware(['auth'])->group(function () {
    Route::get('/landing', [BookController::class, 'landingPage'])->name('landing');
    // Checkout route
    Route::post('/checkout/{id}', [CheckoutController::class, 'checkout'])->name('checkout');
});

// =============================
// Admin + Staff
// =============================
Route::middleware(['auth', 'can:isAdminOrStaff'])->group(function () {
    Route::resource('book', BookController::class);
    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    Route::get('/rfid-scanner', [RFIDScanController::class, 'index'])->name('rfid.scanner');
    Route::post('/rfid-scan', [RFIDScanController::class, 'scan'])->name('rfid.scan');

    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::post('/import-books', [BookImportController::class, 'import'])->name('books.import');

    Route::resource('ebooks', EbookController::class);
    Route::get('/program/{program?}/courses', [App\Http\Controllers\EbookController::class, 'getCourses'])->name('program.courses');
    Route::get('/ebooks/get-courses/{programId}', [EbookController::class, 'getCourses']);
    Route::get('/export-books', [ExportController::class, 'exportBooks'])->name('books.export');
    Route::get('/export-transactions', [ExportController::class, 'exportTransactions'])->name('transactions.export');


    // Attendance Scanner and Book Reports (Admin + Staff)
    
    Route::get('/download-book-report', [BookController::class, 'downloadBookReport'])->name('book.report.download');
    Route::get('/book-report-by-course', [BookController::class, 'bookReportByCourse'])->name('book.report.by.course');

    // Admin
    Route::get('/admin/pending', [StudentController::class, 'pending'])->name('students.pending');
    Route::post('/admin/pending/{id}/approve', [StudentController::class, 'approve'])->name('students.approve');
    Route::post('/admin/pending/{id}/reject', [StudentController::class, 'reject'])->name('students.reject');
    Route::get('/pending', [PendingStudentController::class, 'index'])->name('pending.index');
    
    Route::get('/pending/employees', [PendingEmployeeController::class, 'index'])->name('pending.employees');
    Route::post('/pending/employees/approve/{id}', [PendingEmployeeController::class, 'approve'])->name('employees.approve');
    Route::post('/pending/employees/reject/{id}', [PendingEmployeeController::class, 'reject'])->name('employees.reject');

    Route::get('/students/report', [StudentController::class, 'index'])->name('students.report');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::get('/students/faculty', [StudentController::class, 'faculty'])->name('students.faculty');

    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/update/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/delete/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

        Route::prefix('idcard')->group(function () {
            Route::get('/front/{id}', [EmployeeIdCardController::class, 'front'])->name('employees.id.front');
            Route::get('/back/{id}', [EmployeeIdCardController::class, 'back'])->name('employees.id.back');
            Route::get('/download/{id}', [EmployeeIdCardController::class, 'download'])->name('employees.id.download');
        });
    });
});

// =============================
// Admin-only Routes
// =============================
Route::middleware(['auth', 'can:isAdmin'])->group(function () {
    // Book Logs
    Route::get('/logs', [BookLogController::class, 'index'])->name('logs.index');
    Route::post('/logs', [BookLogController::class, 'store'])->name('logs.store');

    // Prospectus
    Route::prefix('prospectus')->name('prospectus.')->group(function () {
        Route::get('/', [ProspectusController::class, 'index'])->name('index');
        Route::post('/store-program', [ProspectusController::class, 'storeProgram'])->name('storeProgram');
        Route::get('/{program}/years', [ProspectusController::class, 'getProgramYears'])->name('getProgramYears');
    });
    // Course management
    Route::post('/prospectus/{year}/course', [ProspectusController::class, 'storeCourse'])->name('prospectus.storeCourse');
    Route::put('/prospectus/course/{course}', [ProspectusController::class, 'updateCourse'])->name('prospectus.updateCourse');
    Route::delete('/prospectus/course/{course}', [ProspectusController::class, 'destroyCourse'])->name('prospectus.destroyCourse');
    Route::put('/prospectus/program/{program}', [ProspectusController::class, 'updateProgram'])->name('prospectus.updateProgram');
    Route::delete('/prospectus/program/{program}', [ProspectusController::class, 'destroyProgram'])->name('prospectus.destroyProgram');
    // Show form to add subject (course & year are passed via query)
    Route::get('/prospectus/add-subject', [ProspectusController::class, 'createSubject'])->name('prospectus.addSubject');
    // Store new subject
    Route::post('/prospectus/store-subject', [ProspectusController::class, 'storeSubject'])->name('prospectus.storeSubject');

    // Student Management (admin quick-add)
    Route::get('/register-student', [StudentController::class, 'create'])->name('students.create');
    Route::post('/register-student', [StudentController::class, 'store'])->name('students.store');
    Route::get('/idcard/download/{id}', [IdCardController::class, 'download'])->name('idcard.download');


    // Attendance Logs
    Route::get('/attendance-logs', [AttendanceLogController::class, 'index'])->name('attendance_logs.index');
    Route::get('/attendance-logs/export/excel', [AttendanceLogController::class, 'exportExcel'])->name('attendance_logs.export.excel');
    Route::get('/attendance-logs/export/pdf', [AttendanceLogController::class, 'exportPdf'])->name('attendance_logs.export.pdf');

    // File Repository
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
    Route::get('/files/view/{id}', [FileController::class, 'view'])->name('files.view');
    Route::get('/files/download/{id}', [FileController::class, 'download'])->name('files.download');
    Route::delete('/files/delete/{id}', [FileController::class, 'delete'])->name('files.delete');

    // User Management
    Route::get('/view-users', [UserController::class, 'index'])->name('users.index');
    Route::get('/edit-user/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/update-user/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/delete-user/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    
    Route::get('/create-user', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    
    Route::get('/view-users', [UserController::class, 'index'])->name('users.index');
    Route::get('/edit-user/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/update-user/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/delete-user/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/idcard/{id}', [IdCardController::class, 'generate']);
    Route::get('/idcard/front/{id}', [IdCardController::class, 'front']);
    Route::get('/idcard/back/{id}', [IdCardController::class, 'back'])->name('idcard.back');

});
