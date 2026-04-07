# Developing a Student Attendance Management System (Web-Based)

## What Does It Mean?

"Developing a Student Attendance Management System with Web-Based" means building a **web application** — accessible through a browser — that automates the process of recording, tracking, and reporting student attendance.

Instead of using paper-based roll calls or spreadsheets, this system digitizes the entire workflow so that teachers, administrators, and students can manage attendance efficiently from any device with internet access.

---

## Key Concepts

### Student Attendance Management System (SAMS)
A software system designed to:
- Record daily/session-based student attendance (present, absent, late, excused)
- Store student and class information
- Generate attendance reports and statistics
- Notify parents or guardians of absences
- Support decision-making for school administration

### Web-Based
The system is delivered as a **web application**, meaning:
- It runs in a web browser (Chrome, Firefox, Safari, etc.)
- No software installation is needed on the user's device
- It is accessible from desktops, tablets, and mobile phones
- Data is stored on a central server or cloud

---

## Core Features

| Feature | Description |
|---|---|
| **User Authentication** | Login for admins, teachers, and students with role-based access |
| **Student Management** | Add, edit, and organize student profiles |
| **Class/Course Management** | Define classes, sections, subjects, and schedules |
| **Attendance Recording** | Mark attendance per session (present, absent, late, excused) |
| **Dashboard** | Overview of attendance statistics and trends |
| **Reports** | Generate daily, weekly, monthly, and term-based reports |
| **Notifications** | Alert parents/guardians about absences (email/SMS) |
| **Data Export** | Export reports to PDF, Excel, or CSV |

---

## System Users (Roles)

1. **Administrator** — Manages the entire system (users, classes, settings)
2. **Teacher** — Records attendance and views reports for their classes
3. **Student** — Views their own attendance records
4. **Parent/Guardian** *(optional)* — Receives notifications and views child's attendance

---

## Technology Stack

| Layer | Technology |
|---|---|
| **Backend** | Laravel (PHP 8.4) |
| **Admin Frontend** | Laravel + Inertia.js + Vue 3 (built into backend) |
| **Teacher Frontend** | Nuxt 3 + shadcn-vue (separate project, port 3001) |
| **Student Frontend** | Nuxt 3 + shadcn-vue (separate project, port 3002) |
| **CSS** | Tailwind CSS |
| **Database** | SQLite (dev) / MySQL / PostgreSQL (prod) |
| **Auth (Admin)** | Laravel Session-based |
| **Auth (Teacher/Student)** | Laravel Sanctum (API tokens) |

### Project Structure

```
WebSAMS/
├── backend/              ← Laravel + Inertia.js (API + Admin panel)
│   ├── app/Http/Controllers/Dashboard/   ← Admin controllers (Inertia)
│   ├── app/Http/Controllers/Api/         ← API controllers (Teacher/Student)
│   ├── resources/js/Pages/               ← Admin Vue pages
│   ├── routes/web.php                    ← Admin routes (session auth)
│   └── routes/api.php                    ← API routes (Sanctum token auth)
├── frontend-teacher/     ← Nuxt 3 + shadcn-vue (port 3001)
├── frontend-student/     ← Nuxt 3 + shadcn-vue (port 3002)
```

---

## Benefits

- **Accuracy** — Eliminates manual errors in attendance tracking
- **Efficiency** — Saves time for teachers and admin staff
- **Accessibility** — Access from anywhere with an internet connection
- **Real-time Data** — Instant attendance updates and reports
- **Transparency** — Parents and students can view attendance records
- **Scalability** — Easily supports growing number of students and classes

---

## Role Capabilities

### Administrator (Laravel + Inertia — built into backend)

| Feature | Description |
|---|---|
| **Dashboard** | View total students, teachers, classes, and attendance statistics |
| **Manage Users** | Create, read, update, delete all users (admin, teacher, student) |
| **Manage Courses** | Create, read, update, delete courses (e.g. Computer Science, Math) |
| **Manage Subjects** | Create, read, update, delete subjects under courses |
| **Manage Classes** | Create, read, update, delete school classes (e.g. Grade 10-A) |
| **Assign Teachers** | Assign a teacher to teach a specific subject in a specific class |
| **Enroll Students** | Enroll students into classes |
| **View All Attendance** | View, filter, and export attendance records across all classes |
| **Reports** | Generate attendance reports by class, subject, student, or date range |

### Teacher (Nuxt frontend — via API)

| Feature | Description |
|---|---|
| **Dashboard** | View today's schedule and classes to teach |
| **My Classes** | View list of assigned classes and subjects |
| **Mark Attendance** | Select class + subject + date, mark each student as present/absent/late/excused |
| **View Attendance History** | View past attendance records for their own classes |
| **View Student List** | See students enrolled in their assigned classes |

### Student (Nuxt frontend — via API)

| Feature | Description |
|---|---|
| **Dashboard** | View attendance summary (% present, % absent, % late) |
| **My Attendance** | View full attendance history with filters (by subject, date range) |
| **View Profile** | View own profile information |

---

## Database Schema

### 1. users

| Field | Type | Notes |
|---|---|---|
| id | BIGINT | Primary Key, Auto Increment |
| name | VARCHAR | User's full name |
| email | VARCHAR | Unique |
| email_verified_at | TIMESTAMP | Nullable |
| password | VARCHAR | Hashed |
| role | ENUM | `admin`, `teacher`, `student` (default: `student`) |
| phone | VARCHAR | Nullable |
| remember_token | VARCHAR | Nullable |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### 2. courses

| Field | Type | Notes |
|---|---|---|
| id | BIGINT | Primary Key, Auto Increment |
| name | VARCHAR | e.g. "Computer Science" |
| code | VARCHAR | Unique, e.g. "CS" |
| description | TEXT | Nullable |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### 3. school_classes

| Field | Type | Notes |
|---|---|---|
| id | BIGINT | Primary Key, Auto Increment |
| name | VARCHAR | e.g. "Grade 10-A" |
| section | VARCHAR | Nullable, e.g. "A" |
| academic_year | VARCHAR | e.g. "2025-2026" |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### 4. subjects

| Field | Type | Notes |
|---|---|---|
| id | BIGINT | Primary Key, Auto Increment |
| course_id | BIGINT | FK → courses.id (CASCADE DELETE) |
| name | VARCHAR | e.g. "Programming" |
| code | VARCHAR | Unique, e.g. "CS101" |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### 5. class_subject (Teacher Assignment)

| Field | Type | Notes |
|---|---|---|
| id | BIGINT | Primary Key, Auto Increment |
| school_class_id | BIGINT | FK → school_classes.id (CASCADE DELETE) |
| subject_id | BIGINT | FK → subjects.id (CASCADE DELETE) |
| teacher_id | BIGINT | FK → users.id (CASCADE DELETE) |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### 6. class_student (Student Enrollment)

| Field | Type | Notes |
|---|---|---|
| id | BIGINT | Primary Key, Auto Increment |
| school_class_id | BIGINT | FK → school_classes.id (CASCADE DELETE) |
| student_id | BIGINT | FK → users.id (CASCADE DELETE) |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### 7. attendances

| Field | Type | Notes |
|---|---|---|
| id | BIGINT | Primary Key, Auto Increment |
| class_subject_id | BIGINT | FK → class_subject.id (CASCADE DELETE) |
| student_id | BIGINT | FK → users.id (CASCADE DELETE) |
| date | DATE | Attendance date |
| status | ENUM | `present`, `absent`, `late`, `excused` |
| remarks | TEXT | Nullable |
| recorded_by | BIGINT | FK → users.id (teacher who marked) |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

**Unique constraint:** (class_subject_id, student_id, date) — prevents duplicate attendance per student per session per day.

### 8. schedules

| Field | Type | Notes |
|---|---|---|
| id | BIGINT | Primary Key, Auto Increment |
| class_subject_id | BIGINT | FK → class_subject.id (CASCADE DELETE) |
| day_of_week | ENUM | `mon`, `tue`, `wed`, `thu`, `fri` |
| start_time | TIME | e.g. "08:00" |
| end_time | TIME | e.g. "09:30" |
| room | VARCHAR | Nullable, e.g. "Room 201" |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### 9. notifications

| Field | Type | Notes |
|---|---|---|
| id | BIGINT | Primary Key, Auto Increment |
| user_id | BIGINT | FK → users.id (CASCADE DELETE) |
| title | VARCHAR | e.g. "Absence Alert" |
| message | TEXT | Notification content |
| type | ENUM | `absence`, `late`, `general` (default: `general`) |
| is_read | BOOLEAN | Default: false |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Entity Relationship Diagram

```
courses 1──────M subjects
                    │
                    M
                    │
school_classes M────M class_subject M────1 users (teacher)
    │                    │
    M                    1
    │                    │
class_student         attendances M────1 users (recorded_by)
    │                    │
    1                    M
    │                    │
users (student) ────────┘
```

---

## Implementation Status

All 8 phases have been completed.

| Phase | Description | Status |
|---|---|---|
| 1 | Auth Foundation (Sanctum, session auth, role middleware, CORS) | Completed |
| 2 | Admin Dashboard with real data (live stats from DB) | Completed |
| 3 | Admin CRUD — Users, Courses, Subjects | Completed |
| 4 | Admin CRUD — Classes, Teacher Assignment, Student Enrollment | Completed |
| 5 | Admin — Attendance Viewing, Schedules, Reports | Completed |
| 6 | Teacher API + Nuxt Frontend | Completed |
| 7 | Student API + Nuxt Frontend | Completed |
| 8 | Polish (seeder updates, SQLite fixes, guard checks) | Completed |

---

## Detailed Project Structure

```
WebSAMS/
├── backend/                                    ← Laravel 12 + Inertia.js + Vue 3
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Auth/
│   │   │   │   │   └── LoginController.php          ← Admin session login/logout
│   │   │   │   ├── Dashboard/
│   │   │   │   │   ├── DashboardController.php      ← Admin dashboard (live stats)
│   │   │   │   │   ├── UserController.php           ← Users CRUD
│   │   │   │   │   ├── CourseController.php         ← Courses CRUD
│   │   │   │   │   ├── SubjectController.php        ← Subjects CRUD
│   │   │   │   │   ├── SchoolClassController.php    ← Classes CRUD + detail view
│   │   │   │   │   ├── ClassSubjectController.php   ← Teacher assignment
│   │   │   │   │   ├── ClassStudentController.php   ← Student enrollment
│   │   │   │   │   ├── AttendanceController.php     ← View all attendance
│   │   │   │   │   ├── ScheduleController.php       ← Schedules CRUD
│   │   │   │   │   └── ReportController.php         ← Generate reports
│   │   │   │   └── Api/
│   │   │   │       ├── AuthController.php           ← API token login/logout
│   │   │   │       ├── Teacher/
│   │   │   │       │   ├── DashboardController.php  ← Today's schedule
│   │   │   │       │   ├── ClassController.php      ← My classes + students
│   │   │   │       │   └── AttendanceController.php ← Mark + view attendance
│   │   │   │       └── Student/
│   │   │   │           ├── DashboardController.php  ← Attendance summary
│   │   │   │           ├── AttendanceController.php ← Own attendance history
│   │   │   │           └── ProfileController.php    ← Profile + enrolled classes
│   │   │   └── Middleware/
│   │   │       ├── HandleInertiaRequests.php        ← Inertia shared data
│   │   │       └── EnsureUserHasRole.php            ← Role-based access
│   │   └── Models/
│   │       ├── User.php, Course.php, Subject.php
│   │       ├── SchoolClass.php, ClassSubject.php, ClassStudent.php
│   │       ├── Attendance.php, Schedule.php, Notification.php
│   ├── resources/js/
│   │   ├── Layouts/
│   │   │   └── AdminLayout.vue                      ← Sidebar + topbar layout
│   │   ├── Components/
│   │   │   ├── Pagination.vue, FlashMessage.vue, Modal.vue
│   │   ├── Pages/
│   │   │   ├── Auth/Login.vue
│   │   │   ├── Dashboard/Index.vue
│   │   │   ├── Users/Index.vue, Create.vue, Edit.vue
│   │   │   ├── Courses/Index.vue, Create.vue, Edit.vue
│   │   │   ├── Subjects/Index.vue, Create.vue, Edit.vue
│   │   │   ├── Classes/Index.vue, Create.vue, Edit.vue, Show.vue
│   │   │   ├── Attendance/Index.vue
│   │   │   ├── Schedules/Index.vue, Create.vue, Edit.vue
│   │   │   └── Reports/Index.vue, Show.vue
│   ├── routes/
│   │   ├── web.php                                  ← Admin routes (47 routes)
│   │   └── api.php                                  ← API routes (11 endpoints)
│   └── database/
│       ├── migrations/                              ← 13 migrations
│       └── seeders/DatabaseSeeder.php               ← Test data
│
├── frontend-teacher/                               ← Nuxt 3 + shadcn-vue (port 3001)
│   ├── composables/useAuth.ts, useApi.ts
│   ├── middleware/auth.ts
│   ├── layouts/default.vue
│   └── pages/
│       ├── login.vue, index.vue
│       ├── classes/index.vue, [id].vue
│       └── attendance/mark.vue, index.vue
│
├── frontend-student/                               ← Nuxt 3 + shadcn-vue (port 3002)
│   ├── composables/useAuth.ts, useApi.ts
│   ├── middleware/auth.ts
│   ├── layouts/default.vue
│   └── pages/
│       ├── login.vue, index.vue
│       ├── attendance.vue, profile.vue
│
└── WebSAMS-Overview.md
```

---

## API Endpoints

### Public
| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/login` | Login (returns token + user) |

### Authenticated (all roles)
| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/logout` | Logout (revoke token) |
| GET | `/api/user` | Get authenticated user |

### Teacher Endpoints (`/api/teacher/*`)
| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/teacher/dashboard` | Today's schedule |
| GET | `/api/teacher/classes` | List assigned classes |
| GET | `/api/teacher/classes/{id}/students` | Students in a class |
| POST | `/api/teacher/attendance` | Mark attendance (batch) |
| GET | `/api/teacher/attendance` | View attendance history |

### Student Endpoints (`/api/student/*`)
| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/student/dashboard` | Attendance summary (% by subject) |
| GET | `/api/student/attendance` | Own attendance history |
| GET | `/api/student/profile` | Profile + enrolled classes |

---

## Admin Web Routes

| Path | Description |
|---|---|
| `/login` | Admin login page |
| `/admin/dashboard` | Dashboard with live stats |
| `/admin/users` | Users CRUD (list, create, edit, delete) |
| `/admin/courses` | Courses CRUD |
| `/admin/subjects` | Subjects CRUD (filter by course) |
| `/admin/classes` | Classes CRUD |
| `/admin/classes/{id}` | Class detail (assign teachers, enroll students) |
| `/admin/attendance` | View all attendance (filterable) |
| `/admin/schedules` | Schedules CRUD (grouped by day) |
| `/admin/reports` | Generate attendance reports |

---

## How to Run

### Prerequisites
- PHP 8.4+
- Node.js 20+
- Composer

### Setup
```bash
# Backend
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Frontend Teacher
cd frontend-teacher
npm install

# Frontend Student
cd frontend-student
npm install
```

### Start Development Servers
```bash
# Terminal 1: Laravel backend
cd backend && php artisan serve                # http://localhost:8000

# Terminal 2: Vite (for admin panel hot reload)
cd backend && npm run dev

# Terminal 3: Teacher frontend
cd frontend-teacher && npm run dev             # http://localhost:3001

# Terminal 4: Student frontend
cd frontend-student && npm run dev             # http://localhost:3002
```

### Test Credentials

| Role | Email | Password |
|---|---|---|
| Admin | admin@websams.com | password |
| Teacher 1 | teacher1@websams.com | password |
| Teacher 2 | teacher2@websams.com | password |
| Teacher 3 | teacher3@websams.com | password |
| Student 1 | student1@websams.com | password |
| Student 2-20 | student2-20@websams.com | password |

### Seed Data Included
- 1 admin, 3 teachers, 20 students
- 3 courses (Computer Science, Mathematics, English)
- 5 subjects across courses
- 3 classes (Grade 10-A, 10-B, 11-A)
- 5 teacher-subject-class assignments
- 20 student enrollments
- 5 class schedules
- Sample attendance data for the current week

---

## Summary

A **Web-Based Student Attendance Management System (WebSAMS)** replaces traditional paper-based attendance with a modern, digital solution. It centralizes attendance data, automates reporting, and provides real-time access to all stakeholders through a web browser.
