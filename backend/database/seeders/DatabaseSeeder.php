<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\ClassSubject;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Time Slots
        $morning1 = TimeSlot::create(['name' => 'Morning 1', 'start_time' => '08:00', 'end_time' => '09:30', 'type' => 'morning']);
        $morning2 = TimeSlot::create(['name' => 'Morning 2', 'start_time' => '10:00', 'end_time' => '11:30', 'type' => 'morning']);
        $afternoon1 = TimeSlot::create(['name' => 'Afternoon 1', 'start_time' => '13:00', 'end_time' => '14:30', 'type' => 'afternoon']);
        $afternoon2 = TimeSlot::create(['name' => 'Afternoon 2', 'start_time' => '15:00', 'end_time' => '17:00', 'type' => 'afternoon']);
        $evening1 = TimeSlot::create(['name' => 'Evening 1', 'start_time' => '17:30', 'end_time' => '19:00', 'type' => 'evening']);
        $evening2 = TimeSlot::create(['name' => 'Evening 2', 'start_time' => '19:30', 'end_time' => '20:30', 'type' => 'evening']);

        $allTimeSlots = [$morning1, $morning2, $afternoon1, $afternoon2, $evening1, $evening2];

        // Semesters
        Semester::create(['name' => 'Semester 1', 'academic_year' => '2025-2026', 'start_date' => '2025-09-01', 'end_date' => '2026-01-31']);
        Semester::create(['name' => 'Semester 2', 'academic_year' => '2025-2026', 'start_date' => '2026-02-01', 'end_date' => '2026-06-30']);

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@websams.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // Teachers
        $teachers = [];
        foreach (['Mr. John Smith', 'Ms. Sarah Lee', 'Mr. David Chen'] as $i => $name) {
            $teachers[] = User::create([
                'name' => $name,
                'email' => 'teacher' . ($i + 1) . '@websams.com',
                'password' => Hash::make('12345678'),
                'role' => 'teacher',
            ]);
        }

        // Students (with year levels)
        $students = [];
        $studentNames = [
            'Alice Johnson', 'Bob Williams', 'Charlie Brown', 'Diana Ross',
            'Edward Davis', 'Fiona Garcia', 'George Martinez', 'Hannah Wilson',
            'Isaac Anderson', 'Julia Thomas', 'Kevin Taylor', 'Laura Moore',
            'Michael Jackson', 'Nancy White', 'Oscar Harris', 'Patricia Clark',
            'Quinn Lewis', 'Rachel Hall', 'Steven Young', 'Tina King',
        ];
        foreach ($studentNames as $i => $name) {
            $students[] = User::create([
                'name' => $name,
                'email' => 'student' . ($i + 1) . '@websams.com',
                'password' => Hash::make('12345678'),
                'role' => 'student',
                'year_level' => ($i % 4) + 1, // Year 1-4
            ]);
        }

        // Courses
        $cs = Course::create(['name' => 'Computer Science', 'code' => 'CS', 'description' => 'Computer Science Program']);
        $math = Course::create(['name' => 'Mathematics', 'code' => 'MATH', 'description' => 'Mathematics Program']);
        $eng = Course::create(['name' => 'English', 'code' => 'ENG', 'description' => 'English Language Program']);

        // Subjects
        $subjects = [
            Subject::create(['course_id' => $cs->id, 'name' => 'Programming', 'code' => 'CS101']),
            Subject::create(['course_id' => $cs->id, 'name' => 'Data Structures', 'code' => 'CS102']),
            Subject::create(['course_id' => $math->id, 'name' => 'Calculus', 'code' => 'MATH101']),
            Subject::create(['course_id' => $math->id, 'name' => 'Linear Algebra', 'code' => 'MATH102']),
            Subject::create(['course_id' => $eng->id, 'name' => 'English Literature', 'code' => 'ENG101']),
        ];

        // Classes
        $year1A = SchoolClass::create(['name' => 'Year 1 - A', 'section' => 'A', 'academic_year' => '2025-2026']);
        $year1B = SchoolClass::create(['name' => 'Year 1 - B', 'section' => 'B', 'academic_year' => '2025-2026']);
        $year2A = SchoolClass::create(['name' => 'Year 2 - A', 'section' => 'A', 'academic_year' => '2025-2026']);

        // Assign teachers to class-subjects
        $classSubjects = [
            ClassSubject::create(['school_class_id' => $year1A->id, 'subject_id' => $subjects[0]->id, 'teacher_id' => $teachers[0]->id]),
            ClassSubject::create(['school_class_id' => $year1A->id, 'subject_id' => $subjects[2]->id, 'teacher_id' => $teachers[1]->id]),
            ClassSubject::create(['school_class_id' => $year1B->id, 'subject_id' => $subjects[1]->id, 'teacher_id' => $teachers[0]->id]),
            ClassSubject::create(['school_class_id' => $year1B->id, 'subject_id' => $subjects[4]->id, 'teacher_id' => $teachers[2]->id]),
            ClassSubject::create(['school_class_id' => $year2A->id, 'subject_id' => $subjects[3]->id, 'teacher_id' => $teachers[1]->id]),
        ];

        // Enroll students
        foreach (array_slice($students, 0, 7) as $student) {
            ClassStudent::create(['school_class_id' => $year1A->id, 'student_id' => $student->id]);
        }
        foreach (array_slice($students, 7, 7) as $student) {
            ClassStudent::create(['school_class_id' => $year1B->id, 'student_id' => $student->id]);
        }
        foreach (array_slice($students, 14, 6) as $student) {
            ClassStudent::create(['school_class_id' => $year2A->id, 'student_id' => $student->id]);
        }

        // Schedules (weekday morning + weekend full day)
        $weekdays = ['mon', 'tue', 'wed', 'thu', 'fri'];
        $weekends = ['sat', 'sun'];

        // Weekday schedules: morning sessions
        foreach ($classSubjects as $i => $cs) {
            $day = $weekdays[$i % 5];
            Schedule::create(['class_subject_id' => $cs->id, 'time_slot_id' => $morning1->id, 'day_of_week' => $day, 'room' => 'Room ' . (201 + $i)]);
            Schedule::create(['class_subject_id' => $cs->id, 'time_slot_id' => $morning2->id, 'day_of_week' => $day, 'room' => 'Room ' . (201 + $i)]);
        }

        // Weekend schedules for first 2 class-subjects (full day)
        foreach (array_slice($classSubjects, 0, 2) as $i => $cs) {
            $day = $weekends[$i % 2];
            Schedule::create(['class_subject_id' => $cs->id, 'time_slot_id' => $morning1->id, 'day_of_week' => $day, 'room' => 'Room 301']);
            Schedule::create(['class_subject_id' => $cs->id, 'time_slot_id' => $morning2->id, 'day_of_week' => $day, 'room' => 'Room 301']);
            Schedule::create(['class_subject_id' => $cs->id, 'time_slot_id' => $afternoon1->id, 'day_of_week' => $day, 'room' => 'Room 301']);
            Schedule::create(['class_subject_id' => $cs->id, 'time_slot_id' => $afternoon2->id, 'day_of_week' => $day, 'room' => 'Room 301']);
        }

        // Evening schedules for last 2 class-subjects
        foreach (array_slice($classSubjects, 3, 2) as $i => $cs) {
            $day = $weekdays[$i % 5];
            Schedule::create(['class_subject_id' => $cs->id, 'time_slot_id' => $evening1->id, 'day_of_week' => $day, 'room' => 'Room 401']);
            Schedule::create(['class_subject_id' => $cs->id, 'time_slot_id' => $evening2->id, 'day_of_week' => $day, 'room' => 'Room 401']);
        }

        // Sample attendance (with time slots)
        $statuses = ['present', 'absent', 'late', 'excused'];
        $today = now();
        for ($day = 0; $day < 7; $day++) {
            $date = $today->copy()->subDays($day);

            foreach ($classSubjects as $cs) {
                // Determine which time slots this class has on this day
                $dayName = strtolower(substr($date->format('D'), 0, 3));
                $schedules = Schedule::where('class_subject_id', $cs->id)
                    ->where('day_of_week', $dayName)
                    ->get();

                if ($schedules->isEmpty()) continue;

                $classStudents = ClassStudent::where('school_class_id', $cs->school_class_id)->get();
                foreach ($classStudents as $enrollment) {
                    foreach ($schedules as $schedule) {
                        Attendance::create([
                            'class_subject_id' => $cs->id,
                            'student_id' => $enrollment->student_id,
                            'date' => $date->toDateString(),
                            'time_slot_id' => $schedule->time_slot_id,
                            'status' => $statuses[rand(0, 3) < 3 ? 0 : rand(1, 3)],
                            'recorded_by' => $cs->teacher_id,
                        ]);
                    }
                }
            }
        }
    }
}
