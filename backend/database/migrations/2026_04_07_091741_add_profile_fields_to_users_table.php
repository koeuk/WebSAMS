<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Shared fields
            $table->string('id_number')->nullable()->unique()->after('year_level');
            $table->enum('gender', ['male', 'female'])->nullable()->after('id_number');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->text('address')->nullable()->after('date_of_birth');
            $table->string('profile_photo')->nullable()->after('address');
            $table->enum('status', ['active', 'inactive', 'graduated', 'suspended'])->default('active')->after('profile_photo');

            // Student-specific
            $table->string('guardian_name')->nullable()->after('status');
            $table->string('guardian_phone')->nullable()->after('guardian_name');
            $table->date('enrollment_date')->nullable()->after('guardian_phone');

            // Teacher-specific
            $table->string('department')->nullable()->after('enrollment_date');
            $table->string('qualification')->nullable()->after('department');
            $table->date('hire_date')->nullable()->after('qualification');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'id_number', 'gender', 'date_of_birth', 'address', 'profile_photo', 'status',
                'guardian_name', 'guardian_phone', 'enrollment_date',
                'department', 'qualification', 'hire_date',
            ]);
        });
    }
};
