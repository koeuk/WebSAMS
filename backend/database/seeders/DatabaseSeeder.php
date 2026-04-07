<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\ClassSubject;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@websams.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Teachers
        $teachers = [];
        foreach (['Mr. John Smith', 'Ms. Sarah Lee', 'Mr. David Chen'] as $i => $name) {
            $teachers[] = User::create([
                'name' => $name,
                'email' => 'teacher' . ($i + 1) . '@websams.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]);
        }

        // Students
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
                'password' => Hash::make('password'),
                'role' => 'student',
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
        $class10A = SchoolClass::create(['name' => 'Grade 10-A', 'section' => 'A', 'academic_year' => '2025-2026']);
        $class10B = SchoolClass::create(['name' => 'Grade 10-B', 'section' => 'B', 'academic_year' => '2025-2026']);
        $class11A = SchoolClass::create(['name' => 'Grade 11-A', 'section' => 'A', 'academic_year' => '2025-2026']);

        // Assign teachers to class-subjects
        $classSubjects = [
            ClassSubject::create(['school_class_id' => $class10A->id, 'subject_id' => $subjects[0]->id, 'teacher_id' => $teachers[0]->id]),
            ClassSubject::create(['school_class_id' => $class10A->id, 'subject_id' => $subjects[2]->id, 'teacher_id' => $teachers[1]->id]),
            ClassSubject::create(['school_class_id' => $class10B->id, 'subject_id' => $subjects[1]->id, 'teacher_id' => $teachers[0]->id]),
            ClassSubject::create(['school_class_id' => $class10B->id, 'subject_id' => $subjects[4]->id, 'teacher_id' => $teachers[2]->id]),
            ClassSubject::create(['school_class_id' => $class11A->id, 'subject_id' => $subjects[3]->id, 'teacher_id' => $teachers[1]->id]),
        ];

        // Enroll students
        foreach (array_slice($students, 0, 7) as $student) {
            ClassStudent::create(['school_class_id' => $class10A->id, 'student_id' => $student->id]);
        }
        foreach (array_slice($students, 7, 7) as $student) {
            ClassStudent::create(['school_class_id' => $class10B->id, 'student_id' => $student->id]);
        }
        foreach (array_slice($students, 14, 6) as $student) {
            ClassStudent::create(['school_class_id' => $class11A->id, 'student_id' => $student->id]);
        }

        // Schedules
        $days = ['mon', 'tue', 'wed', 'thu', 'fri'];
        $times = [['08:00', '09:30'], ['09:45', '11:15'], ['13:00', '14:30']];
        foreach ($classSubjects as $i => $cs) {
            Schedule::create([
                'class_subject_id' => $cs->id,
                'day_of_week' => $days[$i % 5],
                'start_time' => $times[$i % 3][0],
                'end_time' => $times[$i % 3][1],
                'room' => 'Room ' . (201 + $i),
            ]);
        }

        // Sample attendance for this week
        $statuses = ['present', 'absent', 'late', 'excused'];
        $today = now();
        for ($day = 0; $day < 5; $day++) {
            $date = $today->copy()->subDays($day);
            if ($date->isWeekend()) continue;

            foreach ($classSubjects as $cs) {
                $classStudents = ClassStudent::where('school_class_id', $cs->school_class_id)->get();
                foreach ($classStudents as $enrollment) {
                    Attendance::create([
                        'class_subject_id' => $cs->id,
                        'student_id' => $enrollment->student_id,
                        'date' => $date->toDateString(),
                        'status' => $statuses[rand(0, 3) < 3 ? 0 : rand(1, 3)],
                        'recorded_by' => $cs->teacher_id,
                    ]);
                }
            }
        }
    }
}
