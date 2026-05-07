<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disbursements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            \DB::statement('ALTER TABLE disbursements MODIFY user_id BIGINT UNSIGNED NULL');
        }
        Schema::table('disbursements', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('disbursements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            \DB::statement('ALTER TABLE disbursements MODIFY user_id BIGINT UNSIGNED NOT NULL');
        }
        Schema::table('disbursements', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
