<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pending_employees')) {
            return;
        }

        Schema::table('pending_employees', function (Blueprint $table) {
            if (!Schema::hasColumn('pending_employees', 'firstname')) {
                $table->string('firstname')->nullable();
            }
            if (!Schema::hasColumn('pending_employees', 'lastname')) {
                $table->string('lastname')->nullable();
            }
            if (!Schema::hasColumn('pending_employees', 'employee_id')) {
                $table->string('employee_id')->nullable();
            }
            if (!Schema::hasColumn('pending_employees', 'status')) {
                $table->string('status')->nullable();
            }
            if (!Schema::hasColumn('pending_employees', 'formal_picture')) {
                $table->string('formal_picture')->nullable();
            }
        });

        if (Schema::hasColumn('pending_employees', 'employee_name')) {
            $rows = DB::table('pending_employees')
                ->whereNull('firstname')
                ->whereNotNull('employee_name')
                ->orderBy('id')
                ->get();

            foreach ($rows as $row) {
                $parts = preg_split('/\s+/', trim($row->employee_name), 2);
                DB::table('pending_employees')->where('id', $row->id)->update([
                    'firstname' => $parts[0] ?? $row->employee_name,
                    'lastname' => $parts[1] ?? '',
                ]);
            }
        }

        if (Schema::hasColumn('pending_employees', 'profile_picture')) {
            DB::table('pending_employees')
                ->whereNull('formal_picture')
                ->whereNotNull('profile_picture')
                ->update(['formal_picture' => DB::raw('profile_picture')]);
        }

        if (Schema::hasColumn('pending_employees', 'employee_number')) {
            DB::table('pending_employees')
                ->whereNull('employee_id')
                ->whereNotNull('employee_number')
                ->update(['employee_id' => DB::raw('employee_number')]);
        }
    }

    public function down(): void
    {
        // Non-destructive alignment migration
    }
};
