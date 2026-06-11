<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->string('student_id')->nullable();
            $table->string('id_number')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('course');
            $table->string('year')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('qrcode')->nullable()->unique();
            $table->date('birth_date')->nullable();
            $table->string('blood_type', 5)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('emergency_address')->nullable();
            $table->string('student_signature')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_students');
    }
};
