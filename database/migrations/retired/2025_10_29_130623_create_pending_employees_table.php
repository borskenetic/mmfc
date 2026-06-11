<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pending_employees')) {
            return;
        }

        Schema::create('pending_employees', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys (if using roles & users like students)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            
            // FRONT SIDE
            $table->string('employee_id')->unique()->nullable();
            $table->string('formal_picture')->nullable();
            $table->string('department')->nullable();
            $table->string('employee_name');
            $table->string('position')->nullable();
            $table->string('employee_number')->nullable();
            
            // BACK SIDE
            $table->date('birth_date')->nullable();
            $table->string('sex', 20)->nullable(); // Male, Female, Others
            $table->string('tin_id_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('blood_type', 5)->nullable();
            $table->string('sss_number')->nullable();
            $table->string('hdmf_number')->nullable();
            $table->string('qrcode')->unique()->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('employee_signature')->nullable(); // file path to saved signature

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_employees');
    }
};
