<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medication_requests', function (Blueprint $table) {
            $table->string('student_name')->nullable()->after('id');
            $table->string('course')->nullable()->after('student_name');
            $table->text('reason')->nullable()->after('course');
        });
        Schema::table('medication_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            \DB::statement('ALTER TABLE medication_requests MODIFY user_id BIGINT UNSIGNED NULL');
        } elseif ($driver === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys=off');
            // SQLite: recreate table - complex, skip for now
        }
        Schema::table('medication_requests', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('medication_requests', function (Blueprint $table) {
            $table->dropColumn(['student_name', 'course', 'reason']);
        });
        Schema::table('medication_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            \DB::statement('ALTER TABLE medication_requests MODIFY user_id BIGINT UNSIGNED NOT NULL');
        }
        Schema::table('medication_requests', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
