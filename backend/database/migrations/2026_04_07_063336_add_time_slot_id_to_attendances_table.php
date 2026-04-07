<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('time_slot_id')->nullable()->after('date')->constrained('time_slots')->cascadeOnDelete();
        });

        // Drop old unique and create new one
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['class_subject_id']);
            $table->dropUnique(['class_subject_id', 'student_id', 'date']);
            $table->foreign('class_subject_id')->references('id')->on('class_subject')->cascadeOnDelete();
            $table->unique(['class_subject_id', 'student_id', 'date', 'time_slot_id'], 'attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendance_unique');
            $table->dropForeign(['time_slot_id']);
            $table->dropColumn('time_slot_id');
            $table->unique(['class_subject_id', 'student_id', 'date']);
        });
    }
};
