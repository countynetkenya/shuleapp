# Feature: Eattendance (Exam Attendance)

## Overview
**Controller**: `mvc/controllers/Eattendance.php`  
**Primary Purpose**: Tracks student attendance for specific exams by subject, ensuring only present students are eligible for marks entry.  
**User Roles**: Teachers, Admins, Superadmin  
**Status**: ✅ Active

## Functionality

### Core Features
- Exam-specific attendance tracking (not daily)
- Subject-wise attendance per exam
- Section-based student filtering
- Bulk present/absent marking with AJAX
- One-time attendance per exam/subject/student combination

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `eattendance/index` | Search and view exam attendance | `eattendance` |
| `add()` | `eattendance/add` | Mark attendance for exam/subject | `eattendance` |
| `single_add()` | AJAX POST | Mark single student attendance | `eattendance` |
| `all_add()` | AJAX POST | Mark all students present/absent | `eattendance` |
| `exam_list()` | AJAX POST | Navigate to exam attendance | `eattendance` |
| `subjectcall()` | AJAX POST | Get subjects by class | - |
| `sectioncall()` | AJAX POST | Get sections by class | - |

## Data Layer

### Models Used
- `eattendance_m`: Exam attendance records
- `student_m`: Student information
- `studentrelation_m`: Student-class relationships
- `exam_m`: Exam details
- `classes_m`: Class information
- `section_m`: Section information
- `subject_m`: Subject details

### Database Tables
- `eattendance`: `eattendanceID`, `examID`, `classesID`, `sectionID`, `subjectID`, `studentID`, `s_name`, `eattendance` (Present/Absent), `date`, `year`, `schoolyearID`, `schoolID`
- Related: `exam`, `subject`, `studentrelation`

## Validation Rules

### Search Rules
- **examID**: Required, numeric, must be valid exam
- **classesID**: Required, numeric, must be valid class  
- **subjectID**: Required, numeric, must be valid subject

### Add Rules (includes section)
- **examID**: Required, numeric, must be valid exam
- **classesID**: Required, numeric, must be valid class
- **sectionID**: Required, numeric, must be valid section
- **subjectID**: Required, numeric, must be valid subject

## Dependencies & Interconnections

### Depends On (Upstream)
- **Exam**: Requires exam configuration
- **Subject**: Requires subject setup
- **Classes/Section**: For student filtering
- **Studentrelation**: For enrolled students

### Used By (Downstream)
- **Mark**: May check attendance before allowing mark entry
- **Examreport**: May filter reports by attendance
- **Exam eligibility**: Could restrict exam participation

### Related Features
- **Exam**: Defines which exams need attendance
- **Mark**: Marking typically requires attendance
- **Sattendance**: Daily attendance (different purpose)

## User Flows

### Primary Flow: Mark Exam Attendance
1. Teacher navigates to Eattendance → Add
2. Selects exam, class, section, subject from dropdowns
3. System displays students enrolled in that class/section
4. Auto-creates attendance records (default Absent) for first marking
5. Teacher clicks checkboxes to mark individual students Present
6. OR uses "Mark All" button for bulk operations
7. AJAX calls update database immediately
8. System updates `eattendance` field to "Present" or "Absent"

### Search Flow
1. Navigate to Eattendance index
2. Select exam, class, subject (section optional)
3. View existing attendance for filtering/reporting
4. Can navigate to add/edit from search results

## Edge Cases & Limitations
- **One-Time Marking**: Unlike daily attendance, exam attendance is per-exam event
- **Auto-Creation**: First access creates all student records as "Absent"
- **No Monthly Grid**: Single Present/Absent field per record
- **No Notifications**: No parent/student alerts for exam attendance
- **No Leave Integration**: Doesn't check approved leaves
- **AJAX-Heavy**: All updates via AJAX (no page refresh for better UX)
- **Subject Type Filtering**: Handles both mandatory (type=1) and optional (type=0) subjects

## Configuration
- Tied to school year (`defaultschoolyearID`)
- Filtered by `schoolID`
- No special settings required

## Notes for AI Agents

### Implementation Details
- **Simpler Model**: No monthly columns, just single `eattendance` field (Present/Absent string)
- **Batch Insert**: First marking creates records for all students in `insert_batch_eattendance`
- **AJAX Updates**: `single_add` and `all_add` use `update_eattendance_via_array` for immediate updates
- **Status Values**: "Present", "Absent" (not P/A like other attendance)
- **Checkbox UI**: Frontend uses checkboxes, backend toggles checked/unchecked → Present/Absent

### Business Logic
- **Unique Constraint**: One record per (examID, classesID, sectionID, subjectID, studentID)
- **Search vs Add**: Index for searching, Add for marking (different validation rules)
- **Section Required for Add**: Index can search without section, Add needs it for student filtering
- **Subject Type Handling**: Optional subjects filter by `sroptionalsubjectID` in studentrelation

### Performance
- AJAX-driven interface is fast for user, but creates many small database hits
- Batch insert on first access is efficient
- No complex calculations or notifications = faster than Sattendance

