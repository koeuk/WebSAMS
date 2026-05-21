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
        $morning1   = TimeSlot::create(['name' => ['en' => 'Morning 1',   'km' => 'ព្រឹក ១',   'zh' => '上午 1'], 'start_time' => '08:00', 'end_time' => '09:30', 'type' => 'morning']);
        $morning2   = TimeSlot::create(['name' => ['en' => 'Morning 2',   'km' => 'ព្រឹក ២',   'zh' => '上午 2'], 'start_time' => '10:00', 'end_time' => '11:30', 'type' => 'morning']);
        $afternoon1 = TimeSlot::create(['name' => ['en' => 'Afternoon 1', 'km' => 'រសៀល ១', 'zh' => '下午 1'], 'start_time' => '13:00', 'end_time' => '14:30', 'type' => 'afternoon']);
        $afternoon2 = TimeSlot::create(['name' => ['en' => 'Afternoon 2', 'km' => 'រសៀល ២', 'zh' => '下午 2'], 'start_time' => '15:00', 'end_time' => '17:00', 'type' => 'afternoon']);
        $evening1   = TimeSlot::create(['name' => ['en' => 'Evening 1',   'km' => 'ល្ងាច ១', 'zh' => '晚上 1'], 'start_time' => '17:30', 'end_time' => '19:00', 'type' => 'evening']);
        $evening2   = TimeSlot::create(['name' => ['en' => 'Evening 2',   'km' => 'ល្ងាច ២', 'zh' => '晚上 2'], 'start_time' => '19:30', 'end_time' => '20:30', 'type' => 'evening']);

        $allTimeSlots = [$morning1, $morning2, $afternoon1, $afternoon2, $evening1, $evening2];

        // Semesters
        Semester::create(['name' => ['en' => 'Semester 1', 'km' => 'ឆមាស ១', 'zh' => '第一学期'], 'academic_year' => '2025-2026', 'start_date' => '2025-09-01', 'end_date' => '2026-01-31']);
        Semester::create(['name' => ['en' => 'Semester 2', 'km' => 'ឆមាស ២', 'zh' => '第二学期'], 'academic_year' => '2025-2026', 'start_date' => '2026-02-01', 'end_date' => '2026-06-30']);

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // Teachers
        $teachers = [];
        foreach (['Mr. John Smith', 'Ms. Sarah Lee', 'Mr. David Chen', 'Ms. Emily Clark', 'Mr. James Wong'] as $i => $name) {
            $teachers[] = User::create([
                'name' => $name,
                'email' => 'teacher' . ($i + 1) . '@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'teacher',
            ]);
        }

        // Students (with year levels)
        $students = [];
        $studentNames = ['Alice Johnson', 'Bob Williams', 'Charlie Brown', 'Diana Ross', 'Edward Davis'];
        foreach ($studentNames as $i => $name) {
            $students[] = User::create([
                'name' => $name,
                'email' => 'student' . ($i + 1) . '@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'student',
                'year_level' => ($i % 4) + 1,
            ]);
        }

        // Courses
        $cs   = Course::create([
            'name'        => ['en' => 'Computer Science', 'km' => 'វិទ្យាសាស្ត្រ​កុំព្យូទ័រ', 'zh' => '计算机科学'],
            'code'        => 'CS',
            'description' => ['en' => 'Computer Science Program', 'km' => 'កម្មវិធី​វិទ្យាសាស្ត្រ​កុំព្យូទ័រ', 'zh' => '计算机科学课程'],
        ]);
        $math = Course::create([
            'name'        => ['en' => 'Mathematics', 'km' => 'គណិតវិទ្យា', 'zh' => '数学'],
            'code'        => 'MATH',
            'description' => ['en' => 'Mathematics Program', 'km' => 'កម្មវិធី​គណិតវិទ្យា', 'zh' => '数学课程'],
        ]);
        $eng  = Course::create([
            'name'        => ['en' => 'English', 'km' => 'ភាសា​អង់គ្លេស', 'zh' => '英语'],
            'code'        => 'ENG',
            'description' => ['en' => 'English Language Program', 'km' => 'កម្មវិធី​ភាសា​អង់គ្លេស', 'zh' => '英语语言课程'],
        ]);

        // Subjects
        $subjects = [
            Subject::create(['course_id' => $cs->id,   'name' => ['en' => 'Programming',         'km' => 'ការ​សរសេរ​កម្មវិធី',     'zh' => '编程'],     'code' => 'CS101']),
            Subject::create(['course_id' => $cs->id,   'name' => ['en' => 'Data Structures',     'km' => 'រចនាសម្ព័ន្ធ​ទិន្នន័យ', 'zh' => '数据结构'], 'code' => 'CS102']),
            Subject::create(['course_id' => $math->id, 'name' => ['en' => 'Calculus',            'km' => 'ដេរីវេ​និង​អាំងតេក្រាល', 'zh' => '微积分'],   'code' => 'MATH101']),
            Subject::create(['course_id' => $math->id, 'name' => ['en' => 'Linear Algebra',      'km' => 'ពិជគណិត​លីនេអ៊ែរ',     'zh' => '线性代数'], 'code' => 'MATH102']),
            Subject::create(['course_id' => $eng->id,  'name' => ['en' => 'English Literature',  'km' => 'អក្សរសិល្ប៍​អង់គ្លេស',  'zh' => '英语文学'], 'code' => 'ENG101']),
        ];

        // Classes
        $year1A = SchoolClass::create(['name' => ['en' => 'Year 1 - A', 'km' => 'ឆ្នាំ​ទី​១ - A', 'zh' => '一年级 - A'], 'section' => 'A', 'academic_year' => '2025-2026']);
        $year1B = SchoolClass::create(['name' => ['en' => 'Year 1 - B', 'km' => 'ឆ្នាំ​ទី​១ - B', 'zh' => '一年级 - B'], 'section' => 'B', 'academic_year' => '2025-2026']);
        $year2A = SchoolClass::create(['name' => ['en' => 'Year 2 - A', 'km' => 'ឆ្នាំ​ទី​២ - A', 'zh' => '二年级 - A'], 'section' => 'A', 'academic_year' => '2025-2026']);

        // Assign teachers to class-subjects (one unique teacher per class-subject)
        $classSubjects = [
            ClassSubject::create(['school_class_id' => $year1A->id, 'subject_id' => $subjects[0]->id, 'teacher_id' => $teachers[0]->id]),
            ClassSubject::create(['school_class_id' => $year1A->id, 'subject_id' => $subjects[2]->id, 'teacher_id' => $teachers[1]->id]),
            ClassSubject::create(['school_class_id' => $year1B->id, 'subject_id' => $subjects[1]->id, 'teacher_id' => $teachers[2]->id]),
            ClassSubject::create(['school_class_id' => $year1B->id, 'subject_id' => $subjects[4]->id, 'teacher_id' => $teachers[3]->id]),
            ClassSubject::create(['school_class_id' => $year2A->id, 'subject_id' => $subjects[3]->id, 'teacher_id' => $teachers[4]->id]),
        ];

        // Enroll students (5 students: 2 in year1A, 2 in year1B, 1 in year2A)
        foreach (array_slice($students, 0, 2) as $student) {
            ClassStudent::create(['school_class_id' => $year1A->id, 'student_id' => $student->id]);
        }
        foreach (array_slice($students, 2, 2) as $student) {
            ClassStudent::create(['school_class_id' => $year1B->id, 'student_id' => $student->id]);
        }
        foreach (array_slice($students, 4, 1) as $student) {
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
