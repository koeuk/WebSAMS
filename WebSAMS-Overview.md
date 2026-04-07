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

## Implementation Plan

### Phase 1: Auth Foundation (Completed)
- Sanctum installed for API token auth (teacher/student)
- Session-based auth for admin login
- Role middleware (`EnsureUserHasRole`) for route protection
- CORS configured for localhost:3001 and :3002
- Admin login page (Inertia + Vue)
- API login endpoint (`POST /api/login`)

### Phase 2: Admin Dashboard with Real Data (Completed)
- Dashboard shows live stats: total students, teachers, classes, today's attendance rate
- Recent attendance table with student name, class, subject, date, status

### Phase 3: Admin CRUD — Users, Courses, Subjects
- Shared Vue components: DataTable, Pagination, Modal, FlashMessage
- Users: list (filter by role), create, edit, delete
- Courses: list, create, edit, delete
- Subjects: list (filter by course), create, edit, delete

### Phase 4: Admin CRUD — Classes, Assignments, Enrollment
- Classes: list, create, edit, delete, show (detail page)
- Assign teachers to class-subjects
- Enroll students into classes

### Phase 5: Admin — Attendance, Schedules, Reports
- Attendance viewing with filters (class, subject, date range, status)
- Schedules CRUD (day, time, room)
- Reports generation (filter + per-student breakdown)

### Phase 6: Teacher API + Nuxt Frontend
- API: dashboard (today's schedule), my classes, students list, mark attendance, attendance history
- Nuxt: login, dashboard, classes, mark attendance page, attendance history

### Phase 7: Student API + Nuxt Frontend
- API: dashboard (attendance summary %), own attendance, profile
- Nuxt: login, dashboard, attendance history, profile

### Phase 8: Polish
- Form request validation classes
- Error handling + loading states
- Pagination on all lists
- Guard checks (ownership verification)

### Build Order

```
Phase 1 (Auth) ────────────────────────┐
    │                                   │
    ├──► Phase 2 (Dashboard)            │
    │                                   │
    ├──► Phase 3 (Users/Courses/Subj.)  │
    │         │                         │
    │         ▼                         │
    │    Phase 4 (Classes/Enrollment)   │
    │         │                         │
    │         ▼                         │
    │    Phase 5 (Attendance/Reports)   │
    │                                   │
    ├──► Phase 6 (Teacher) ◄────────────┘
    │         │
    │         ▼
    └──► Phase 7 (Student)
              │
              ▼
         Phase 8 (Polish)
```

### How to Run

```bash
# Admin (Laravel + Inertia)
cd backend && php artisan serve          # http://localhost:8000
cd backend && npm run dev                # Vite dev server

# Teacher (Nuxt)
cd frontend-teacher && npm run dev       # http://localhost:3001

# Student (Nuxt)
cd frontend-student && npm run dev       # http://localhost:3002
```

### Test Credentials

| Role | Email | Password |
|---|---|---|
| Admin | admin@websams.com | password |
| Teacher | teacher1@websams.com | password |
| Student | student1@websams.com | password |

---

## Summary

A **Web-Based Student Attendance Management System (WebSAMS)** replaces traditional paper-based attendance with a modern, digital solution. It centralizes attendance data, automates reporting, and provides real-time access to all stakeholders through a web browser.
