# WebSAMS — Web-Based Student Attendance Management System

**Author:** KOS KOEUK  
**Degree:** Bachelor of Information Technology in Software Engineering (BIT-SE)  
**University:** BELTEI International University, Phnom Penh, Cambodia  
**Supervisor:** Mr. RASMEY YIM  
**Year:** 2026

---

## Project Structure

```
WebSAMS/
├── backend/              Laravel 13 + Inertia.js + Vue 3   → http://localhost:8000
├── teacher/              Nuxt 3 + shadcn-vue               → http://localhost:3001
├── student/              Nuxt 3 + shadcn-vue               → http://localhost:3002
├── CLAUDE.md             ← this file
├── WebSAMS-Overview.md   full feature/schema docs
└── THESIS.md             full bachelor's thesis document
```

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13 (PHP 8.3) |
| Admin frontend | Inertia.js 3 + Vue 3.5 (built into backend) |
| Teacher/Student frontend | Nuxt 3.16 |
| UI components | shadcn-nuxt, Reka-UI, Radix-Vue |
| Styling | TailwindCSS 4.2 |
| Icons | Lucide Icons |
| Charts | Chart.js + vue-chartjs |
| Database (dev) | SQLite |
| Database (prod) | MySQL / PostgreSQL |
| Admin auth | Laravel session-based |
| API auth | Laravel Sanctum (token-based) |

---

## How to Run

```bash
# Terminal 1 — Laravel backend + admin panel
cd backend && php artisan serve                 # http://localhost:8000

# Terminal 2 — Vite (admin panel hot reload)
cd backend && npm run dev

# Terminal 3 — Teacher frontend
cd teacher && npm run dev                       # http://localhost:3001

# Terminal 4 — Student frontend
cd student && npm run dev                       # http://localhost:3002
```

### Fresh database

```bash
cd backend && php artisan migrate:fresh --seed
```

---

## Test Credentials (all passwords: `12345678`)

| Role | Email |
|---|---|
| Admin | admin@gmail.com |
| Teacher 1–5 | teacher1@gmail.com … teacher5@gmail.com |
| Student 1–5 | student1@gmail.com … student5@gmail.com |

---

## Roles & Portals

### Admin — `http://localhost:8000/admin/*` (session auth)
- Dashboard with live stats
- CRUD: Users, Courses, Subjects, Classes, Schedules, Semesters, Time Slots, Settings
- View/filter/export all attendance records
- Generate reports

### Teacher — `http://localhost:3001` (Sanctum token)
- Dashboard (today's schedule)
- My classes + student roster
- Mark attendance manually (check-off roster)
- Generate attendance token session (time-limited code for students)
- Review / approve / reject excuse requests
- Post announcements
- Notifications

### Student — `http://localhost:3002` (Sanctum token)
- Dashboard (attendance summary %)
- Attendance history (filter by subject / date)
- Submit attendance token to mark present
- Submit / track excuse requests
- View announcements
- Notifications + profile

---

## Key Feature: Token-Based Attendance

1. Teacher creates an attendance session → system generates a short alphanumeric token (valid 15–120 min)
2. Teacher displays the token (projected or verbal)
3. Student opens the student portal → Attend page → submits the token
4. System validates: token not expired, student enrolled in that class, not already marked
5. Attendance record created as `present`

---

## Database — 18 Migrations (all `create_*`, no `add_column_*`)

Migration order matters — `time_slots` runs before `attendances` and `schedules`.

| Migration file | Table |
|---|---|
| `0001_01_01_000000_create_users_table` | users, password_reset_tokens, sessions |
| `0001_01_01_000001_create_cache_table` | cache |
| `0001_01_01_000002_create_jobs_table` | jobs |
| `2026_04_07_044049_create_time_slots_table` | time_slots |
| `2026_04_07_044050_create_courses_table` | courses |
| `2026_04_07_044050_create_school_classes_table` | school_classes |
| `2026_04_07_044051_create_subjects_table` | subjects |
| `2026_04_07_044052_create_class_subject_table` | class_subject |
| `2026_04_07_044053_create_class_student_table` | class_student |
| `2026_04_07_044054_create_attendances_table` | attendances |
| `2026_04_07_045201_create_notifications_table` | notifications |
| `2026_04_07_045201_create_schedules_table` | schedules |
| `2026_04_07_050201_create_personal_access_tokens_table` | personal_access_tokens |
| `2026_04_07_054432_create_semesters_table` | semesters |
| `2026_04_07_100730_create_settings_table` | settings |
| `2026_04_20_075205_create_announcements_table` | announcements |
| `2026_04_20_075205_create_excuse_requests_table` | excuse_requests |
| `2026_04_20_075205_create_qr_sessions_table` | qr_sessions (attendance tokens) |

---

## Key Database Entities

### users
All roles in one table. Role field: `admin | teacher | student`.

| Field | Notes |
|---|---|
| role | enum: admin, teacher, student |
| phone, year_level | general |
| id_number | unique, e.g. STU-2025-001 |
| gender, date_of_birth, address, profile_photo | profile |
| status | active, inactive, graduated, suspended |
| guardian_name, guardian_phone, enrollment_date | students only |
| department, qualification, hire_date | teachers only |

### attendances
Unique constraint: `(class_subject_id, student_id, date, time_slot_id)` — named `attendance_unique`.

| Field | Notes |
|---|---|
| class_subject_id | FK → class_subject |
| student_id | FK → users |
| date | attendance date |
| time_slot_id | FK → time_slots |
| status | present, absent, late, excused |
| recorded_by | FK → users (teacher) |

### schedules
`day_of_week` is `string(10)` — supports mon/tue/wed/thu/fri/sat/sun.  
Uses `time_slot_id` FK instead of raw start/end time columns.

### time_slots (predefined seed data)
| Name | Time | Type |
|---|---|---|
| Morning 1 | 08:00–09:30 | morning |
| Morning 2 | 10:00–11:30 | morning |
| Afternoon 1 | 13:00–14:30 | afternoon |
| Afternoon 2 | 15:00–17:00 | afternoon |
| Evening 1 | 17:30–19:00 | evening |
| Evening 2 | 19:30–20:30 | evening |

---

## API Endpoints

Base URL: `http://localhost:8000/api`  
Auth header: `Authorization: Bearer <token>`

### Public
| Method | Endpoint | Description |
|---|---|---|
| POST | `/login` | Returns token + user |
| POST | `/logout` | Revoke token |
| GET | `/user` | Authenticated user |

### Teacher (`/api/teacher/*`)
| Method | Endpoint | Description |
|---|---|---|
| GET | `/dashboard` | Today's schedule |
| GET | `/classes` | Assigned classes |
| GET | `/classes/{id}/students` | Students in class |
| POST | `/classes/{id}/attendance` | Mark attendance (manual) |
| POST | `/attendance-sessions` | Create token session |
| GET | `/attendance-sessions` | List token sessions |
| GET | `/excuse-requests` | Pending excuse requests |
| POST | `/excuse-requests/{id}/approve` | Approve |
| POST | `/excuse-requests/{id}/reject` | Reject |
| GET | `/announcements` | List announcements |
| POST | `/announcements` | Create announcement |
| GET | `/notifications` | Notifications |

### Student (`/api/student/*`)
| Method | Endpoint | Description |
|---|---|---|
| GET | `/dashboard` | Attendance summary % |
| GET | `/attendance` | Own attendance history |
| POST | `/attend` | Submit token → mark present |
| GET | `/excuse-requests` | Own excuse requests |
| POST | `/excuse-requests` | Submit new excuse request |
| GET | `/announcements` | Announcements |
| GET | `/notifications` | Notifications |
| POST | `/notifications/{id}/read` | Mark notification read |

---

## Admin Web Routes (`/admin/*`)

| Path | Description |
|---|---|
| `/login` | Admin login |
| `/admin/dashboard` | Live stats dashboard |
| `/admin/users` | Users CRUD |
| `/admin/courses` | Courses CRUD |
| `/admin/subjects` | Subjects CRUD |
| `/admin/classes` | Classes CRUD |
| `/admin/classes/{id}` | Class detail (assign teachers, enroll students) |
| `/admin/attendance` | View/filter all attendance (+ Clear filter button) |
| `/admin/bulk-attendance` | Bulk mark attendance |
| `/admin/schedules` | Schedules CRUD |
| `/admin/semesters` | Semesters CRUD |
| `/admin/time-slots` | Time slots CRUD |
| `/admin/reports` | Generate reports |
| `/admin/notifications` | Notifications |
| `/admin/settings` | System settings |

---

## Backend File Layout

```
backend/
├── app/Http/Controllers/
│   ├── Auth/LoginController.php
│   ├── Dashboard/
│   │   ├── DashboardController.php
│   │   ├── UserController.php
│   │   ├── CourseController.php
│   │   ├── SubjectController.php
│   │   ├── SchoolClassController.php
│   │   ├── ClassSubjectController.php
│   │   ├── ClassStudentController.php
│   │   ├── AttendanceController.php
│   │   ├── ScheduleController.php
│   │   └── ReportController.php
│   └── Api/
│       ├── AuthController.php
│       ├── Teacher/
│       │   ├── DashboardController.php
│       │   ├── ClassController.php
│       │   └── AttendanceController.php
│       └── Student/
│           ├── DashboardController.php
│           ├── AttendanceController.php
│           └── ProfileController.php
├── app/Http/Middleware/
│   ├── HandleInertiaRequests.php
│   └── EnsureUserHasRole.php
├── app/Models/
│   ├── User.php
│   ├── Course.php, Subject.php
│   ├── SchoolClass.php, ClassSubject.php, ClassStudent.php
│   ├── Attendance.php, Schedule.php, TimeSlot.php
│   ├── Notification.php, Semester.php
│   ├── Announcement.php, ExcuseRequest.php, QrSession.php
│   └── Setting.php
├── resources/js/
│   ├── Layouts/AdminLayout.vue
│   ├── Components/Pagination.vue, FlashMessage.vue, Modal.vue
│   └── Pages/
│       ├── Auth/Login.vue
│       ├── Dashboard/Index.vue
│       ├── Users/, Courses/, Subjects/, Classes/
│       ├── Attendance/Index.vue   ← has Clear filter button
│       ├── Schedules/, Reports/
│       └── Profile/Edit.vue
├── routes/web.php                 ← admin routes (session auth)
├── routes/api.php                 ← API routes (Sanctum)
└── database/
    ├── migrations/                ← 18 clean create_* migrations
    └── seeders/DatabaseSeeder.php ← 1 admin + 5 teachers + 5 students
```

---

## Seed Data Summary

| Type | Count | Details |
|---|---|---|
| Admin | 1 | admin@gmail.com |
| Teachers | 5 | teacher1–5@gmail.com |
| Students | 5 | student1–5@gmail.com |
| Courses | 3 | Computer Science, Mathematics, English |
| Subjects | 5 | CS101, CS102, MATH101, MATH102, ENG101 |
| Classes | 3 | Year 1-A, Year 1-B, Year 2-A |
| Class-subjects | 5 | One unique teacher per class-subject |
| Enrollments | 5 | 2 in Year 1-A, 2 in Year 1-B, 1 in Year 2-A |
| Time slots | 6 | Morning×2, Afternoon×2, Evening×2 |
| Semesters | 2 | Semester 1 & 2, AY 2025-2026 |
| Schedules | ~14 | Weekday mornings + weekend + evening slots |
| Attendance | ~varies | Sample data for the past 7 days |
