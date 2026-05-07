<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_settings', function (Blueprint $table) {
            $table->id();
            $table->string('module')->unique(); // categories, medications, requests, reports
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default modules
        DB::table('module_settings')->insert([
            ['module' => 'categories', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'medications', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'requests',   'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'reports',    'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('module_settings');
    }
};
