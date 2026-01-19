# Feature: Sattendance (Student Attendance)

## Overview
**Controller**: `mvc/controllers/Sattendance.php`  
**Primary Purpose**: Manages daily attendance tracking for students with support for both class-based and subject-based attendance modes. Includes automated email/SMS notifications for absences.  
**User Roles**: Teachers (can mark own class), Admins, Superadmin  
**Status**: ✅ Active

## Functionality

### Core Features
- Monthly attendance tracking with 31-day calendar grid
- Two attendance modes: class-based or subject-based (configured in settings)
- Bulk attendance marking by class/section
- Individual student attendance history viewing
- Automated absence notifications (email/SMS) to parents
- Leave application integration (approved leaves marked automatically)
- Holiday and weekend day validation
- PDF report generation and email sending
- Integration with student exam results in notification templates

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `sattendance/index/{classID}` | List students by class for attendance | `sattendance_view` or own class |
| `add()` | `sattendance/add` | Mark attendance for a specific date | `sattendance` |
| `save_attendance()` | AJAX POST | Save attendance data for students | `sattendance` |
| `view()` | `sattendance/view/{studentID}/{classID}` | View student attendance history | `sattendance_view` |
| `print_preview()` | `sattendance/print_preview/{studentID}/{classID}` | Generate PDF attendance report | `sattendance_view` |
| `send_mail()` | AJAX POST | Email attendance report | `sattendance_view` |
| `student_list()` | AJAX POST | Get students by class | - |
| `subjectall()` | AJAX POST | Get subjects by class | - |
| `sectionall()` | AJAX POST | Get sections by class | - |

## Data Layer

### Models Used
- `sattendance_m`: Core attendance records (class-based mode)
- `subjectattendance_m`: Subject-wise attendance records (subject-based mode)
- `student_m`: Student basic information
- `studentrelation_m`: Student class/section relationships
- `classes_m`: Class information
- `section_m`: Section information
- `subject_m`: Subject information
- `teacher_m`: Teacher information
- `leaveapplication_m`: Approved leave applications
- `mailandsmstemplate_m`: Notification templates
- `mark_m`, `exam_m`, `grade_m`: For result table in notifications

### Database Tables
- `sattendance`: `attendanceID`, `studentID`, `classesID`, `sectionID`, `monthyear`, `a1-a31` (P/A/L/H), `schoolyearID`, `schoolID`, `userID`, `usertype`
- `subjectattendance`: Same as above + `subjectID`
- `leaveapplication`: Tracks approved leaves to mark as 'L'
- `holiday`: Holiday validation
- `setting`: `attendance` mode (class/subject), `attendance_notification` (email/sms/none)

## Validation Rules

### Add Attendance Rules
- **classesID**: Required, numeric, max 11, must exist
- **sectionID**: Required, numeric, max 11, must exist
- **subjectID** (subject mode): Required, numeric, max 11, must exist
- **date**: Required, dd-mm-yyyy format, not future, not holiday, not weekend, within school year

### Save Attendance Rules
- **day**: Required, numeric (1-31)
- **classes**: Required, numeric
- **section**: Required
- **subject** (subject mode): Required
- **monthyear**: Required (MM-YYYY)
- **attendance[]**: Required array of attendance status (P/A/L/H)

### Send Mail Rules
- **to**: Required, valid email, max 60
- **subject**: Required
- **message**: Optional
- **id**: Required student ID
- **set**: Required class ID

## Dependencies & Interconnections

### Depends On (Upstream)
- **Student**: Requires students enrolled in classes
- **Classes**: Requires class structure
- **Section**: Requires section assignments
- **Subject** (subject mode): Requires subject configuration
- **Holiday**: For date validation
- **Schoolyear**: For academic year context
- **Leaveapplication**: For marking approved leaves
- **Setting**: For attendance mode and notification settings

### Used By (Downstream)
- **Attendancereport**: Generates summary reports from attendance data
- **Mailandsms**: Uses attendance data for parent notifications
- **Exam/Mark**: May use attendance for eligibility

### Related Features
- **Tattendance**: Teacher attendance (similar structure)
- **Eattendance**: Exam attendance (per-exam tracking)
- **Leaveapplication**: Integrates with leave dates
- **Studentreport**: May include attendance statistics

## User Flows

### Primary Flow: Mark Daily Attendance
1. Teacher/Admin navigates to Sattendance
2. Selects class from dropdown
3. System loads students for that class
4. User clicks "Add Attendance"
5. Selects date (validates not holiday/weekend)
6. If subject mode: selects section and subject
7. System displays attendance grid with students
8. User marks attendance (P/A) for each student
9. System auto-creates month record if first time
10. On save, system validates and updates database
11. If configured, sends SMS/email to parents of absent students

### Secondary Flow: View Student Attendance
1. Navigate to Sattendance index with class selected
2. Click "View" on student row
3. System loads all attendance records for student
4. Displays monthly calendar with P/A/L/H markers
5. Shows leave applications overlaid on calendar
6. Option to print PDF or email report

### Notification Flow (Absence Alerts)
1. When attendance saved, system filters students marked 'A'
2. Retrieves parent contact (email/phone)
3. Loads configured notification template
4. Replaces tags ([name], [date], [class], etc.) with actual data
5. If email mode: sends via CI email library
6. If SMS mode: sends via configured gateway (Clickatell/Twilio/Bulk/MSG91)

## Edge Cases & Limitations

### Discovered from Code
- **Month Boundaries**: Attendance tied to `monthyear` (MM-YYYY), switching months requires new record
- **Attendance Mode**: Changing `setting.attendance` from class to subject (or vice versa) doesn't migrate existing data
- **Optional Subjects**: In subject mode, only students with matching optional subject ID can be marked
- **Teacher Access**: Teachers can only mark attendance for own class (stored in session)
- **Batch Insertion**: First attendance of month auto-creates records for all students
- **Leave Integration**: Approved leaves auto-mark as 'L' in attendance grid (visual only, not stored)
- **Holiday/Weekend Validation**: Backend validation only (no frontend calendar blocking)
- **Result Table in Notifications**: Complex mark calculation may fail if exam/grade data missing
- **Email/SMS Failures**: No retry mechanism or failure logging

### Performance Considerations
- Batch update uses `update_batch` for efficiency (CodeIgniter method)
- Monthly records reduce row count (31 columns vs 31 rows per student)
- Loading attendance for view iterates all months (could be slow for long year)
- Tag replacement in notifications is O(n*m) where n=tags, m=students

## Configuration

### Required Settings (`setting` table)
- **attendance**: `'class'` or `'subject'` - determines tracking mode
- **attendance_notification**: `'email'`, `'sms'`, or `null` - notification method
- **attendance_notification_template**: Template ID for absence notifications
- **attendance_smsgateway**: `'clickatell'`, `'twilio'`, `'bulk'`, `'msg91'`

### Environment Variables
- SMS gateway credentials (Clickatell API, Twilio SID/Token, etc.)
- Email SMTP settings (CI email config)

## Notes for AI Agents

### Implementation Details
- **Monthly Storage**: Days stored as columns `a1` through `a31` with values P/A/L/H
- **Batch Operations**: Uses CodeIgniter's `insert_batch` and `update_batch` for performance
- **Dynamic Column Updates**: `'a'.abs($day)` creates column name dynamically (a1, a2, etc.)
- **Subject Mode Complexity**: Loads `subjectattendance_m` conditionally in constructor based on setting
- **Leave Application Logic**: Generates date array from approved leaves (60\*60\*24 timestamp iteration)
- **Notification Tags**: 30+ template tags replaced via `tagConvertor` method (includes result table generation)

### Business Logic Gotchas
- **Permissions**: `permissionChecker('sattendance')` for marking, `sattendance_view` for viewing
- **Teacher Restriction**: Teachers see only `$this->data['myclass']` from session
- **School Year Lock**: Can only mark attendance if current year matches `school_year` setting (or superadmin)
- **Class Validation**: Multiple callbacks ensure class/section/subject exist and are valid
- **Date Callbacks**: Complex validation chain: format → future → holiday → weekend → school year range
- **Optional Subject Filter**: In subject mode, filters students by `sroptionalsubjectID` for type 0 subjects

### Performance Warnings
- **Tag Conversion**: `tagConvertor` method is expensive (30+ str_replace calls per student per notification)
- **Result Table Generation**: `resultTableEmail`/`resultTableSMS` queries marks, exams, grades (nested loops)
- **Mark Array Building**: `getMarkArray` does complex joins and calculations (avoid in loops)
- **Batch Size**: No pagination on student list (could timeout on 1000+ student class)

### Common Pitfalls
- **Date Format**: Frontend expects dd-mm-yyyy, backend stores yyyy-mm-dd (conversion required)
- **School Year Context**: Always filtered by `defaultschoolyearID` session variable
- **Section 0**: Valid section ID (means "no section"), not error condition
- **Holiday Format**: Stored as quoted array string `'["01-01-2024","02-01-2024"]'`
- **SMS Gateway Selection**: No fallback if primary gateway fails

