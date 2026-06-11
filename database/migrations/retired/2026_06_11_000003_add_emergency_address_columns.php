<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['students', 'pending_students'] as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'emergency_address')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->string('emergency_address')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['students', 'pending_students'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'emergency_address')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropColumn('emergency_address');
                });
            }
        }
    }
};
