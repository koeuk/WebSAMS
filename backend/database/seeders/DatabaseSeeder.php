<?php

namespace Database\Seeders;

use App\Models\Semester;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Time Slots
        TimeSlot::firstOrCreate(['name->en' => 'Morning 1'], ['name' => ['en' => 'Morning 1', 'km' => 'ព្រឹក ១', 'zh' => '上午 1'], 'start_time' => '08:00', 'end_time' => '09:30', 'type' => 'morning']);
        TimeSlot::firstOrCreate(['name->en' => 'Morning 2'], ['name' => ['en' => 'Morning 2', 'km' => 'ព្រឹក ២', 'zh' => '上午 2'], 'start_time' => '10:00', 'end_time' => '11:30', 'type' => 'morning']);
        TimeSlot::firstOrCreate(['name->en' => 'Afternoon 1'], ['name' => ['en' => 'Afternoon 1', 'km' => 'រសៀល ១', 'zh' => '下午 1'], 'start_time' => '13:00', 'end_time' => '14:30', 'type' => 'afternoon']);
        TimeSlot::firstOrCreate(['name->en' => 'Afternoon 2'], ['name' => ['en' => 'Afternoon 2', 'km' => 'រសៀល ២', 'zh' => '下午 2'], 'start_time' => '15:00', 'end_time' => '17:00', 'type' => 'afternoon']);
        TimeSlot::firstOrCreate(['name->en' => 'Evening 1'], ['name' => ['en' => 'Evening 1', 'km' => 'ល្ងាច ១', 'zh' => '晚上 1'], 'start_time' => '17:30', 'end_time' => '19:00', 'type' => 'evening']);
        TimeSlot::firstOrCreate(['name->en' => 'Evening 2'], ['name' => ['en' => 'Evening 2', 'km' => 'ល្ងាច ២', 'zh' => '晚上 2'], 'start_time' => '19:30', 'end_time' => '20:30', 'type' => 'evening']);

        // Semesters for 2023 to 2027
        $academicYears = ['2023-2024', '2024-2025', '2025-2026', '2026-2027'];
        foreach ($academicYears as $year) {
            $startYear = (int) explode('-', $year)[0];
            $endYear = (int) explode('-', $year)[1];

            Semester::firstOrCreate(
                ['academic_year' => $year, 'name->en' => 'Semester 1'],
                [
                    'name' => ['en' => 'Semester 1', 'km' => 'ឆមាស ១', 'zh' => '第一学期'],
                    'start_date' => "$startYear-09-01",
                    'end_date' => "$endYear-01-31",
                ]
            );

            Semester::firstOrCreate(
                ['academic_year' => $year, 'name->en' => 'Semester 2'],
                [
                    'name' => ['en' => 'Semester 2', 'km' => 'ឆមាស ២', 'zh' => '第二学期'],
                    'start_date' => "$endYear-02-01",
                    'end_date' => "$endYear-06-30",
                ]
            );
        }

        // Admin
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]
        );

        // Call specialized seeders
        $this->call([
            KhmerAcademicDatasetSeeder::class,
            SectionDataSeeder::class,
        ]);
    }
}

