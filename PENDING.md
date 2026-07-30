# Student Placement Portal — PENDING Features

> Last updated: 2026-07-30

---

## Module 1 — Authentication

- [ ] **Change Password** — for both Admin and Student
  - `AuthController::changePassword()` — validate old password, hash new one
  - `views/auth/change_password.php` — form page
  - Add "Change Password" link to student Settings page and admin header dropdown

---


## Module 4 — Placement Drives

- [ ] **Publish / Unpublish button** — `status` field exists (Active/Upcoming/Closed) but no explicit one-click Publish action button on the drive card for Admin

---

## Module 5 — Resume Management ⚡ HIGH

- [ ] `resumes` table in database (`id`, `student_id`, `file_path`, `uploaded_at`)
- [ ] `ResumeController.php`
  - `upload()` — student uploads PDF resume
  - `replace()` — student replaces existing resume
  - `download()` — student/admin downloads resume
- [ ] `views/resume/upload.php` — Resume upload page for student
- [ ] Only PDF accepted (max 5MB); linked to logged-in student session
- [ ] Show resume status on student profile (`Uploaded ✅` or `Not Uploaded ⚠️`)

---

## Module 6 — Apply for Placement (Student) ⚡ HIGH

- [ ] `applications` table in database:
  ```
  id, placement_id, student_id, resume_id, status (Pending/Shortlisted/Selected/Rejected), applied_at
  ```
- [ ] `ApplicationController.php`
  - `apply()` — student applies to a placement drive
  - `myApplications()` — student views own applications list
  - Guard: student cannot apply to the same drive twice
  - Guard: drive must be `Active` to accept applications
  - Guard: student CGPA must meet drive's `minimum_cgpa`
- [ ] `views/application/my_applications.php` — Student's applied placements + status tracker
- [ ] **Apply Now** button on company vacancy card triggers the apply flow (currently opens external URL)
- [ ] Add `apply` and `myApplications` routes to student allowed modules in `index.php`

---

## Module 7 — Applications Management (Admin) ⚡ HIGH

- [ ] Admin actions in `ApplicationController.php`:
  - `index()` — View all applications (filterable by drive, status, student)
  - `updateStatus()` — Change status: `Pending → Shortlisted → Selected → Rejected`
- [ ] `views/admin/applications.php` — Applications list showing:
  - Student Name, Enroll No, Department
  - Applied Placement Drive & Company
  - Resume Download Link
  - Applied Date
  - Status Dropdown / Action Buttons
- [ ] Add **Applications** to Admin navigation sidebar

---

## Student Dashboard (Dedicated Page)

- [ ] `views/student/dashboard.php` — Student home page with:
  - Profile Summary Card (Name, Enroll, Branch, CGPA)
  - Resume Status Card (`Uploaded` / `Not Uploaded` with Upload button)
  - Active Placement Drives count
  - Applied Placements count + recent application status
  - Quick links: View Profile | Upload Resume | Browse Drives | My Applications

---

## Admin Dashboard Improvements

- [ ] Add **Companies** count card
- [ ] Add **Total Applications** count card
- [ ] Add **Selected Students** count card
- [ ] Add **Recent Applications** table (last 5 entries)

---

## Recommended Build Order

```
1. Resume Upload (Module 5)
       ↓
2. Apply for Placement (Module 6)
       ↓
3. Admin Applications View + Status (Module 7)
       ↓
4. Student My Applications Page
       ↓
5. Change Password
       ↓
6. Student Dashboard Page
       ↓
7. Admin Dashboard Improvements
       ↓
8. Division Field + Publish Button
```
