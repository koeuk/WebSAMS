# WebSAMS — GitHub Issues

Copy-paste ready. Each section below = one GitHub issue.

**Format per issue:**
- **Title** → paste into issue title
- **Body** → paste into issue description
- **Labels** → add via sidebar
- **Milestone** → assign via sidebar

**Total:** 51 issues across 8 milestones.

---

## Setup before creating issues

### Labels to create
`backend`, `frontend`, `admin`, `teacher`, `student`, `api`, `auth`, `database`, `setup`, `infra`, `polish`, `qa`, `docs`, `thesis`, `refactor`

### Milestones to create
1. Phase 1 — Project Setup
2. Phase 2 — Database
3. Phase 3 — Authentication
4. Phase 4 — Admin Portal
5. Phase 5 — Teacher Portal
6. Phase 6 — Student Portal
7. Phase 7 — Polish & QA
8. Phase 8 — Documentation

---

# 🏁 Milestone 1 — Project Setup

---

## Issue #1
**Title:** Initialize monorepo & three apps

**Body:**
Set up the WebSAMS monorepo with three sub-apps.

**Tasks:**
- [ ] Create folders: `backend/`, `teacher/`, `student/`
- [ ] Run `composer create-project laravel/laravel backend`
- [ ] Run `npx nuxi init teacher`
- [ ] Run `npx nuxi init student`
- [ ] Add `CLAUDE.md` at repo root

**Labels:** `setup`, `infra`
**Milestone:** Phase 1 — Project Setup

---

## Issue #2
**Title:** Install backend dependencies (Laravel, Sanctum, Inertia)

**Body:**
Install and configure Laravel backend stack.

**Tasks:**
- [ ] Install Laravel Sanctum
- [ ] Install Inertia.js + Vue 3 + Vite
- [ ] Configure SQLite for dev
- [ ] Configure CORS for ports 3001 and 3002
- [ ] Set Sanctum stateful domains

**Labels:** `setup`, `backend`
**Milestone:** Phase 1 — Project Setup

---

## Issue #3
**Title:** Install frontend dependencies (teacher + student)

**Body:**
Install shared Nuxt 3 + shadcn-vue stack in both portal apps.

**Tasks:**
- [ ] Install `shadcn-nuxt`, `reka-ui`, `radix-vue`
- [ ] Install `tailwindcss@4`, `lucide-vue-next`
- [ ] Install `chart.js`, `vue-chartjs`
- [ ] Configure dev ports: teacher=3001, student=3002

**Labels:** `setup`, `frontend`
**Milestone:** Phase 1 — Project Setup

---

# 🗄️ Milestone 2 — Database

---

## Issue #4
**Title:** Create 18 database migrations in correct order

**Body:**
Build `create_*` migrations only (no `add_column_*`). Order matters for FK resolution.

**Order:**
1. users, cache, jobs
2. time_slots (MUST run before attendances & schedules)
3. courses, school_classes, subjects
4. class_subject, class_student
5. attendances (unique: `class_subject_id, student_id, date, time_slot_id`)
6. schedules, notifications, personal_access_tokens
7. semesters, settings, announcements, excuse_requests, qr_sessions

**Acceptance:** `php artisan migrate:fresh` runs clean on SQLite.

**Labels:** `backend`, `database`
**Milestone:** Phase 2 — Database

---

## Issue #5
**Title:** Create 15 Eloquent models with relationships

**Body:**
Models: User, Course, Subject, SchoolClass, ClassSubject, ClassStudent, Attendance, Schedule, TimeSlot, Semester, Setting, Notification, Announcement, ExcuseRequest, QrSession.

**Key relations:**
- `User`: role scopes (teachers, students), hasMany attendances/announcements
- `Attendance` belongsTo ClassSubject, User (student + recorder), TimeSlot
- `Schedule` belongsTo ClassSubject, TimeSlot
- `QrSession` belongsTo ClassSubject + Teacher; hasMany attendances

**Labels:** `backend`, `database`
**Milestone:** Phase 2 — Database

---

## Issue #6
**Title:** Write DatabaseSeeder with demo data

**Body:**
Seed dataset that exercises every screen.

**Contents:**
- 1 admin, 5 teachers, 5 students (password `12345678`)
- 3 courses, 5 subjects, 3 classes, 5 enrollments
- 6 time slots (morning×2, afternoon×2, evening×2)
- 2 semesters (AY 2025-2026 S1 & S2)
- ~14 schedules
- 7 days of sample attendance

**Labels:** `backend`, `database`
**Milestone:** Phase 2 — Database

---

# 🔐 Milestone 3 — Authentication

---

## Issue #7
**Title:** Admin session login (Inertia)

**Body:**
Build session-based admin login flow.

**Tasks:**
- [ ] `LoginController` with credential validation
- [ ] Role-gated redirect after login
- [ ] Login Inertia page

**Labels:** `backend`, `auth`
**Milestone:** Phase 3 — Authentication

---

## Issue #8
**Title:** Sanctum API auth for teacher/student

**Body:**
Token-based API auth.

**Endpoints:**
- [ ] `POST /api/login` → returns `{ token, user }`
- [ ] `POST /api/logout` → revokes token
- [ ] `GET /api/user` → returns authenticated user

**Labels:** `backend`, `auth`, `api`
**Milestone:** Phase 3 — Authentication

---

## Issue #9
**Title:** Role middleware

**Body:**
Implement `EnsureUserHasRole` middleware.

**Usage:** `role:admin`, `role:teacher`, `role:student`
- [ ] Apply to web routes (admin)
- [ ] Apply to API routes (teacher/student)

**Labels:** `backend`, `auth`
**Milestone:** Phase 3 — Authentication

---

# 🖥️ Milestone 4 — Admin Portal

---

## Issue #10
**Title:** Admin Dashboard with live stats

**Body:**
Build admin landing page with overview stats.

**Tasks:**
- [ ] DashboardController
- [ ] Dashboard/Index.vue page
- [ ] Display total users, classes, today's attendance %, etc.

**Labels:** `backend`, `admin`, `frontend`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #11
**Title:** Users CRUD

**Body:**
Full CRUD for users (admin, teachers, students).

- [ ] UserController (index, create, store, edit, update, destroy)
- [ ] Users Inertia pages (Index, Create, Edit)
- [ ] Role-specific fields (guardian for student, hire_date for teacher)

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #12
**Title:** Courses CRUD

**Body:**
Manage courses (CS, Math, English, etc.).

- [ ] CourseController
- [ ] Courses pages

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #13
**Title:** Subjects CRUD

**Body:**
Manage subjects (CS101, MATH101, etc.).

- [ ] SubjectController
- [ ] Subjects pages

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #14
**Title:** Classes CRUD + assign teachers + enroll students

**Body:**
Manage school classes + their composition.

- [ ] SchoolClassController
- [ ] ClassSubjectController (assign teacher per subject)
- [ ] ClassStudentController (enroll students)
- [ ] Classes Index + Detail pages

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #15
**Title:** Time Slots CRUD

**Body:**
Manage class time slots (morning/afternoon/evening).

- [ ] TimeSlotController
- [ ] TimeSlots pages

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #16
**Title:** Schedules CRUD

**Body:**
Manage weekly schedule per class-subject.

- [ ] ScheduleController
- [ ] Schedules pages
- [ ] Support all 7 days (mon-sun)

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #17
**Title:** Semesters CRUD

**Body:**
Manage academic semesters.

- [ ] SemesterController
- [ ] Semesters pages

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #18
**Title:** Attendance view/filter + Clear filter button

**Body:**
Browse all attendance records with filters.

- [ ] AttendanceController
- [ ] Attendance/Index.vue
- [ ] Filters: class, subject, date range, status
- [ ] Clear filter button

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #19
**Title:** Bulk Attendance entry

**Body:**
Mark attendance for an entire class at once.

- [ ] BulkAttendanceController
- [ ] BulkAttendance page
- [ ] Roster checkbox grid

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #20
**Title:** Reports (filter + CSV export)

**Body:**
Generate attendance reports.

- [ ] ReportController
- [ ] Reports page with filters
- [ ] CSV export endpoint

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #21
**Title:** Settings (system config)

**Body:**
Manage system-wide settings.

- [ ] SettingController
- [ ] Settings page
- [ ] Attendance threshold (clamped 0–100)
- [ ] Other config keys

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #22
**Title:** Student Tracking dashboard (low-attendance alerts)

**Body:**
Highlight students below attendance threshold.

- [ ] StudentTrackingController
- [ ] StudentTracking page
- [ ] Pull threshold from Settings

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

## Issue #23
**Title:** Notifications + Profile pages (admin)

**Body:**
Admin notification center + profile edit.

- [ ] NotificationController
- [ ] ProfileController
- [ ] Notifications + Profile pages

**Labels:** `backend`, `admin`
**Milestone:** Phase 4 — Admin Portal

---

# 👨‍🏫 Milestone 5 — Teacher Portal

---

## Issue #24
**Title:** Teacher API — Dashboard (today's schedule)

**Body:**
`GET /api/teacher/dashboard` returns today's classes for the logged-in teacher.

**Labels:** `backend`, `api`, `teacher`
**Milestone:** Phase 5 — Teacher Portal

---

## Issue #25
**Title:** Teacher API — Classes & student roster

**Body:**
- [ ] `GET /api/teacher/classes` — list assigned classes
- [ ] `GET /api/teacher/classes/{id}/students` — roster

**Labels:** `backend`, `api`, `teacher`
**Milestone:** Phase 5 — Teacher Portal

---

## Issue #26
**Title:** Teacher API — Manual mark attendance

**Body:**
`POST /api/teacher/classes/{id}/attendance` — bulk mark roster.

**Labels:** `backend`, `api`, `teacher`
**Milestone:** Phase 5 — Teacher Portal

---

## Issue #27
**Title:** Teacher API — Token session create/list

**Body:**
- [ ] `POST /api/teacher/attendance-sessions` — create token (valid 15–120 min)
- [ ] `GET /api/teacher/attendance-sessions` — list sessions

**Labels:** `backend`, `api`, `teacher`
**Milestone:** Phase 5 — Teacher Portal

---

## Issue #28
**Title:** Teacher API — Excuse requests (approve/reject)

**Body:**
- [ ] `GET /api/teacher/excuse-requests` — list pending
- [ ] `POST /api/teacher/excuse-requests/{id}/approve`
- [ ] `POST /api/teacher/excuse-requests/{id}/reject`
- [ ] On approve: flip attendance to `excused`

**Labels:** `backend`, `api`, `teacher`
**Milestone:** Phase 5 — Teacher Portal

---

## Issue #29
**Title:** Teacher API — Announcements & notifications

**Body:**
- [ ] `GET/POST /api/teacher/announcements`
- [ ] `GET /api/teacher/notifications`

**Labels:** `backend`, `api`, `teacher`
**Milestone:** Phase 5 — Teacher Portal

---

## Issue #30
**Title:** Teacher UI — auth + dashboard

**Body:**
- [ ] Login page with Sanctum token storage
- [ ] Dashboard showing today's schedule
- [ ] Auth composable / Pinia store attaching `Authorization: Bearer …`

**Labels:** `frontend`, `teacher`
**Milestone:** Phase 5 — Teacher Portal

---

## Issue #31
**Title:** Teacher UI — classes + roster

**Body:**
- [ ] My classes list page
- [ ] Class detail with student roster

**Labels:** `frontend`, `teacher`
**Milestone:** Phase 5 — Teacher Portal

---

## Issue #32
**Title:** Teacher UI — attendance pages

**Body:**
- [ ] Manual attendance entry (check-off roster)
- [ ] Attendance history per class

**Labels:** `frontend`, `teacher`
**Milestone:** Phase 5 — Teacher Portal

---

## Issue #33
**Title:** Teacher UI — token generator screen

**Body:**
- [ ] Generate attendance token
- [ ] Display token large + countdown timer
- [ ] List active/expired tokens

**Labels:** `frontend`, `teacher`
**Milestone:** Phase 5 — Teacher Portal

---

## Issue #34
**Title:** Teacher UI — excuses, announcements, notifications, profile

**Body:**
- [ ] Excuse requests review page
- [ ] Announcement create/list
- [ ] Notifications inbox
- [ ] Profile edit

**Labels:** `frontend`, `teacher`
**Milestone:** Phase 5 — Teacher Portal

---

# 🎓 Milestone 6 — Student Portal

---

## Issue #35
**Title:** Student API — Dashboard (attendance %)

**Body:**
`GET /api/student/dashboard` — attendance summary % per subject.

**Labels:** `backend`, `api`, `student`
**Milestone:** Phase 6 — Student Portal

---

## Issue #36
**Title:** Student API — Attendance history

**Body:**
`GET /api/student/attendance` — own history, filterable by subject/date.

**Labels:** `backend`, `api`, `student`
**Milestone:** Phase 6 — Student Portal

---

## Issue #37
**Title:** Student API — Submit token → mark present

**Body:**
`POST /api/student/attend` validates and creates attendance record.

**Validation:**
- [ ] Token exists and not expired
- [ ] Student enrolled in the token's class_subject
- [ ] Not already marked for that date + time slot
- [ ] On success: insert as `present`

**Labels:** `backend`, `api`, `student`
**Milestone:** Phase 6 — Student Portal

---

## Issue #38
**Title:** Student API — Excuse requests (submit/track)

**Body:**
- [ ] `GET /api/student/excuse-requests` — own requests
- [ ] `POST /api/student/excuse-requests` — submit new

**Labels:** `backend`, `api`, `student`
**Milestone:** Phase 6 — Student Portal

---

## Issue #39
**Title:** Student API — Announcements & notifications

**Body:**
- [ ] `GET /api/student/announcements`
- [ ] `GET /api/student/notifications`
- [ ] `POST /api/student/notifications/{id}/read`

**Labels:** `backend`, `api`, `student`
**Milestone:** Phase 6 — Student Portal

---

## Issue #40
**Title:** Student UI — auth + dashboard

**Body:**
- [ ] Login page with Sanctum token storage
- [ ] Dashboard with attendance % summary chart

**Labels:** `frontend`, `student`
**Milestone:** Phase 6 — Student Portal

---

## Issue #41
**Title:** Student UI — attendance page

**Body:**
- [ ] Attendance history table
- [ ] Filter by subject / date

**Labels:** `frontend`, `student`
**Milestone:** Phase 6 — Student Portal

---

## Issue #42
**Title:** Student UI — token submit page

**Body:**
- [ ] Token input form
- [ ] Submit → success/error toast
- [ ] Show today's attendance result

**Labels:** `frontend`, `student`
**Milestone:** Phase 6 — Student Portal

---

## Issue #43
**Title:** Student UI — excuses, announcements, notifications, profile

**Body:**
- [ ] Excuse request submit + tracking
- [ ] Announcements list
- [ ] Notifications inbox
- [ ] Profile edit

**Labels:** `frontend`, `student`
**Milestone:** Phase 6 — Student Portal

---

# ✨ Milestone 7 — Polish & QA

---

## Issue #44
**Title:** Shadcn-vue audit across all three portals

**Body:**
Ensure consistent shadcn components everywhere.
- [ ] Replace any custom buttons/inputs with shadcn equivalents
- [ ] Standardize Modal, Pagination, Card, Table

**Labels:** `frontend`, `polish`
**Milestone:** Phase 7 — Polish & QA

---

## Issue #45
**Title:** Standardize pagination via base controller

**Body:**
Move pagination limit into a base controller method shared by dashboard controllers.

**Labels:** `backend`, `refactor`
**Milestone:** Phase 7 — Polish & QA

---

## Issue #46
**Title:** Empty / loading / error states

**Body:**
Follow `EMPTY-COMPONENT-GUIDE.md`.
- [ ] Empty state component
- [ ] Loading skeleton
- [ ] Error display
- [ ] Apply to all list pages

**Labels:** `frontend`, `polish`
**Milestone:** Phase 7 — Polish & QA

---

## Issue #47
**Title:** Manual test pass with seeded credentials

**Body:**
Walk through golden paths with each role.

**Credentials:** all passwords `12345678`
- admin@gmail.com
- teacher1@gmail.com … teacher5@gmail.com
- student1@gmail.com … student5@gmail.com

**Flows:**
- [ ] Admin CRUD all entities
- [ ] Teacher creates token + reviews excuses
- [ ] Student submits token + sees attendance

**Labels:** `qa`
**Milestone:** Phase 7 — Polish & QA

---

## Issue #48
**Title:** Browser smoke test (Playwright)

**Body:**
Automated smoke test of critical flows.
- [ ] Login (3 roles)
- [ ] Token attendance happy path
- [ ] Excuse approval flow

**Labels:** `qa`
**Milestone:** Phase 7 — Polish & QA

---

# 📄 Milestone 8 — Documentation

---

## Issue #49
**Title:** Finalize CLAUDE.md + WebSAMS-Overview.md

**Body:**
- [ ] Update CLAUDE.md to match final state
- [ ] Complete WebSAMS-Overview.md feature/schema docs

**Labels:** `docs`
**Milestone:** Phase 8 — Documentation

---

## Issue #50
**Title:** Write THESIS.md chapters 3–5

**Body:**
- [ ] Chapter 3: System Design (from Phases 1–2)
- [ ] Chapter 4: Implementation (from Phases 3–8)
- [ ] Chapter 5: Testing (from Phase 9)

**Labels:** `docs`, `thesis`
**Milestone:** Phase 8 — Documentation

---

## Issue #51
**Title:** Generate diagrams (ER + architecture + sequence)

**Body:**
- [ ] ER diagram from 18 migrations
- [ ] System architecture diagram (3 apps + DB)
- [ ] Sequence diagram: token-attendance flow

**Labels:** `docs`, `thesis`
**Milestone:** Phase 8 — Documentation

---

# Summary

| Milestone | Issues | Count |
|---|---|---|
| 1 — Project Setup | #1–#3 | 3 |
| 2 — Database | #4–#6 | 3 |
| 3 — Authentication | #7–#9 | 3 |
| 4 — Admin Portal | #10–#23 | 14 |
| 5 — Teacher Portal | #24–#34 | 11 |
| 6 — Student Portal | #35–#43 | 9 |
| 7 — Polish & QA | #44–#48 | 5 |
| 8 — Documentation | #49–#51 | 3 |
| **Total** | | **51** |
