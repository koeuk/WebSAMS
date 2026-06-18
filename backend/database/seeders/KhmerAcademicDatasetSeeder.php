<?php

namespace Database\Seeders;

use App\Models\ClassStudent;
use App\Models\ClassSubject;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KhmerAcademicDatasetSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('12345678');

        $teachers = $this->seedTeachers($password);
        $courses = $this->seedCourses();
        $subjects = $this->seedSubjects($courses);
        $classes = $this->seedClasses();
        $students = $this->seedStudents($password);

        $this->assignSubjectsToClasses($classes, $subjects, $teachers);
        $this->enrollStudents($classes, $students);
    }

    private function seedTeachers(string $password): array
    {
        $names = [
            'លោក សុខ វណ្ណា',
            'អ្នកស្រី ចាន់ សុភា',
            'លោក ហេង វិសាល',
            'អ្នកស្រី គឹម ស្រីនាង',
            'លោក ម៉ៅ រដ្ឋា',
        ];

        return array_map(
            fn (string $name, int $index) => User::updateOrCreate(
                ['email' => 'khmer.teacher' . ($index + 1) . '@gmail.com'],
                [
                    'name' => $name,
                    'password' => $password,
                    'role' => 'teacher',
                    'status' => 'active',
                    'department' => 'BELTEI International University',
                ],
            ),
            $names,
            array_keys($names),
        );
    }

    private function seedCourses(): array
    {
        $courses = [
            ['code' => 'CS', 'en' => 'Computer Science', 'km' => 'វិទ្យាសាស្ត្រកុំព្យូទ័រ', 'zh' => '计算机科学'],
            ['code' => 'BUS', 'en' => 'Business Management', 'km' => 'គ្រប់គ្រងពាណិជ្ជកម្ម', 'zh' => '工商管理'],
            ['code' => 'ACC', 'en' => 'Accounting', 'km' => 'គណនេយ្យ', 'zh' => '会计学'],
            ['code' => 'ENG', 'en' => 'English Language', 'km' => 'ភាសាអង់គ្លេស', 'zh' => '英语语言'],
            ['code' => 'MATH', 'en' => 'Mathematics', 'km' => 'គណិតវិទ្យា', 'zh' => '数学'],
        ];

        $created = [];
        foreach ($courses as $course) {
            $created[$course['code']] = Course::updateOrCreate(
                ['code' => $course['code']],
                [
                    'name' => $this->translations($course['en'], $course['km'], $course['zh']),
                    'description' => $this->translations(
                        $course['en'] . ' study program',
                        'កម្មវិធីសិក្សា ' . $course['km'],
                        $course['zh'] . '课程',
                    ),
                ],
            );
        }

        return $created;
    }

    private function seedSubjects(array $courses): array
    {
        $subjects = [
            'CS' => [
                ['CS101', 'Computer Fundamentals', 'មូលដ្ឋានគ្រឹះកុំព្យូទ័រ', '计算机基础'],
                ['CS102', 'Programming', 'ការសរសេរកម្មវិធី', '程序设计'],
                ['CS201', 'Data Structures', 'រចនាសម្ព័ន្ធទិន្នន័យ', '数据结构'],
                ['CS202', 'Database Systems', 'មូលដ្ឋានទិន្នន័យ', '数据库系统'],
                ['CS301', 'Software Engineering', 'វិស្វកម្មកម្មវិធី', '软件工程'],
            ],
            'BUS' => [
                ['BUS101', 'Principles of Management', 'គោលការណ៍គ្រប់គ្រង', '管理学原理'],
                ['BUS102', 'Marketing Fundamentals', 'ទីផ្សារមូលដ្ឋាន', '市场营销基础'],
                ['BUS201', 'Organizational Behavior', 'អាកប្បកិរិយាអង្គការ', '组织行为学'],
                ['BUS202', 'Business Finance', 'ហិរញ្ញវត្ថុអាជីវកម្ម', '企业财务'],
                ['BUS301', 'Business Strategy', 'យុទ្ធសាស្ត្រអាជីវកម្ម', '商业战略'],
            ],
            'ACC' => [
                ['ACC101', 'Basic Accounting', 'គណនេយ្យមូលដ្ឋាន', '基础会计'],
                ['ACC102', 'Financial Accounting', 'គណនេយ្យហិរញ្ញវត្ថុ', '财务会计'],
                ['ACC201', 'Cost Accounting', 'គណនេយ្យចំណាយ', '成本会计'],
                ['ACC202', 'Auditing', 'សវនកម្ម', '审计学'],
                ['ACC301', 'Taxation', 'ពន្ធដារ', '税务'],
            ],
            'ENG' => [
                ['ENG101', 'English Grammar', 'វេយ្យាករណ៍អង់គ្លេស', '英语语法'],
                ['ENG102', 'Reading and Writing', 'ការអាននិងសរសេរ', '阅读与写作'],
                ['ENG201', 'English Conversation', 'ការសន្ទនាអង់គ្លេស', '英语会话'],
                ['ENG202', 'Business Writing', 'ការសរសេរអាជីវកម្ម', '商务写作'],
                ['ENG301', 'English Literature', 'អក្សរសិល្ប៍អង់គ្លេស', '英语文学'],
            ],
        ];

        $created = [];
        foreach ($subjects as $courseCode => $courseSubjects) {
            foreach ($courseSubjects as [$code, $en, $km, $zh]) {
                $created[] = Subject::updateOrCreate(
                    ['code' => $code],
                    [
                        'course_id' => $courses[$courseCode]->id,
                        'name' => $this->translations($en, $km, $zh),
                    ],
                );
            }
        }

        return $created;
    }

    private function seedClasses(): array
    {
        $academicYears = ['2023-2024', '2024-2025', '2025-2026', '2026-2027'];
        $sections = ['A', 'B', 'C', 'D', 'E'];
        $classes = [];

        foreach ($academicYears as $academicYear) {
            foreach ($sections as $sectionIndex => $section) {
                $yearLevel = ($sectionIndex % 4) + 1;

                $classes[] = SchoolClass::updateOrCreate(
                    [
                        'academic_year' => $academicYear,
                        'section' => $section,
                    ],
                    [
                        'name' => $this->translations(
                            'Year ' . $yearLevel . ' - Group ' . $section . ' (' . $academicYear . ')',
                            'ឆ្នាំទី ' . $yearLevel . ' ក្រុម ' . $section . ' (' . $academicYear . ')',
                            $yearLevel . '年级 - ' . $section . '组 (' . $academicYear . ')',
                        ),
                    ],
                );
            }
        }

        return $classes;
    }

    private function seedStudents(string $password): array
    {
        $familyNames = ['សុខ', 'ចាន់', 'ហេង', 'គឹម', 'ម៉ៅ', 'លី', 'អ៊ុក', 'វ៉ាន់', 'សាន', 'ពេជ្រ', 'ជា', 'នុត', 'យ៉ុន'];
        $givenNames = ['ដារ៉ា', 'សុភា', 'វិសាល', 'ស្រីនាង', 'រដ្ឋា', 'សុវណ្ណា', 'ចន្ទ្រា', 'មករា', 'ស្រីពេជ្រ', 'បញ្ញា', 'វីរៈ', 'សំណាង'];
        $students = [];
        $index = 1;

        foreach ($familyNames as $familyName) {
            foreach ($givenNames as $givenName) {
                $academicStartYear = 2023 + (($index - 1) % 4);

                $students[] = User::updateOrCreate(
                    ['email' => 'student' . $index . '@gmail.com'],
                    [
                        'name' => $familyName . ' ' . $givenName,
                        'password' => $password,
                        'role' => 'student',
                        'phone' => '010' . str_pad((string) $index, 6, '0', STR_PAD_LEFT),
                        'year_level' => (($index - 1) % 4) + 1,
                        'id_number' => 'STU' . $academicStartYear . str_pad((string) $index, 4, '0', STR_PAD_LEFT),
                        'gender' => $index % 2 === 0 ? 'female' : 'male',
                        'status' => 'active',
                        'guardian_name' => 'អាណាព្យាបាល ' . $familyName,
                        'guardian_phone' => '011' . str_pad((string) $index, 6, '0', STR_PAD_LEFT),
                        'enrollment_date' => $academicStartYear . '-09-01',
                    ],
                );

                $index++;
            }
        }

        return $students;
    }

    private function assignSubjectsToClasses(array $classes, array $subjects, array $teachers): void
    {
        foreach ($classes as $classIndex => $class) {
            $subject = $subjects[$classIndex % count($subjects)];
            $teacher = $teachers[$classIndex % count($teachers)];

            ClassSubject::updateOrCreate(
                [
                    'school_class_id' => $class->id,
                    'subject_id' => $subject->id,
                ],
                ['teacher_id' => $teacher->id],
            );
        }
    }

    private function enrollStudents(array $classes, array $students): void
    {
        foreach ($students as $studentIndex => $student) {
            $class = $classes[$studentIndex % count($classes)];

            ClassStudent::updateOrCreate(
                [
                    'school_class_id' => $class->id,
                    'student_id' => $student->id,
                ],
                [],
            );
        }
    }

    private function translations(string $en, string $km, string $zh): array
    {
        return [
            'en' => $en,
            'km' => $km,
            'zh' => $zh,
        ];
    }
}