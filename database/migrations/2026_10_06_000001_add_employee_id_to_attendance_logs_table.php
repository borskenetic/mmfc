<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->foreignId('employee_id')
                ->nullable()
                ->after('student_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('student_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('employee_id');
            $table->unsignedBigInteger('student_id')->nullable(false)->change();
        });
    }
};
