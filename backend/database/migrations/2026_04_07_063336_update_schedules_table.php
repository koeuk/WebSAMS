<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->string('day_of_week', 10)->change();
            $table->foreignId('time_slot_id')->nullable()->after('class_subject_id')->constrained('time_slots')->cascadeOnDelete();
            $table->dropColumn(['start_time', 'end_time']);
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->dropForeign(['time_slot_id']);
            $table->dropColumn('time_slot_id');
        });
    }
};
