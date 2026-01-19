# Feature: Tattendance (Teacher Attendance)

## Overview
**Controller**: `mvc/controllers/Tattendance.php`  
**Primary Purpose**: Manages daily attendance tracking for teachers with monthly calendar view and PDF reporting capabilities.  
**User Roles**: Admins, HR Staff, Superadmin (Teachers can view own attendance)  
**Status**: ✅ Active

## Functionality

### Core Features
- Monthly attendance tracking for all teachers
- Bulk attendance marking for specific date
- Individual teacher attendance history viewing
- Leave application integration
- Holiday and weekend validation
- PDF report generation and email distribution

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `tattendance/index` | List all teachers or redirect to own attendance | `tattendance_view` or self |
| `add()` | `tattendance/add` | Mark attendance for specific date | `tattendance` |
| `save_attendance()` | AJAX POST | Save teacher attendance data | `tattendance` |
| `view()` | `tattendance/view/{teacherID}` | View teacher attendance history | `tattendance_view` or self |
| `print_preview()` | `tattendance/print_preview/{teacherID}` | Generate PDF attendance report | `tattendance_view` or self |
| `send_mail()` | AJAX POST | Email attendance report | `tattendance_view` or self |

## Data Layer

### Models Used
- `tattendance_m`: Teacher attendance records
- `teacher_m`: Teacher information
- `leaveapplication_m`: Approved teacher leave applications

### Database Tables
- `tattendance`: `tattendanceID`, `teacherID`, `usertypeID`, `monthyear`, `a1-a31` (P/A/L/H), `schoolyearID`, `schoolID`
- `teacher`: Teacher details
- `leaveapplication`: For approved leaves (marked as 'L')

## Validation Rules

### Add Attendance Rules
- **date**: Required, dd-mm-yyyy format, not future, not holiday, not weekend, within school year

### Save Attendance Rules
- **day**: Required, numeric (1-31)
- **monthyear**: Required (MM-YYYY)
- **attendance[]**: Required array of P/A status

### Send Mail Rules
- **id**: Required teacher ID, numeric
- **to**: Required, valid email
- **subject**: Required
- **message**: Optional

## Dependencies & Interconnections

### Depends On (Upstream)
- **Teacher**: Requires teacher records
- **Holiday**: For date validation
- **Schoolyear**: For academic year context
- **Leaveapplication**: For marking approved teacher leaves

### Used By (Downstream)
- **Salaryreport**: May calculate salary deductions based on attendance
- **Leaveapplicationreport**: Cross-references with leave data
- **HR Reports**: General staff attendance statistics

### Related Features
- **Sattendance**: Student attendance (similar structure)
- **Uattendance**: General user attendance
- **Leaveapplication**: Leave tracking for teachers
- **Manage_salary**: Salary calculations may use attendance

## User Flows

### Primary Flow: Mark Teacher Attendance
1. HR/Admin navigates to Tattendance → Add
2. Selects date (validates not holiday/weekend/future)
3. System displays all teachers with attendance grid
4. Auto-creates monthly records if first time marking this month
5. User marks P (Present) or A (Absent) for each teacher
6. Saves via AJAX, updates database
7. System returns success/error status

### Secondary Flow: View Teacher Attendance
1. Navigate to Tattendance index
2. Click teacher name or view own attendance
3. System loads all attendance records by month
4. Displays monthly calendars with P/A/L markers
5. Leave applications overlaid as 'L'
6. Options to print PDF or email report

## Edge Cases & Limitations
- **Monthly Structure**: Same as Sattendance (a1-a31 columns)
- **Teacher Self-View**: Teachers without `tattendance_view` permission redirected to own attendance
- **School Year Lock**: Attendance only editable for current school year (except superadmin)
- **No Notifications**: Unlike student attendance, no automated parent/admin notifications
- **Leave Integration**: Visual only (approved leaves shown as 'L' but not stored in attendance)

## Configuration
- Uses global holiday and weekend day settings from session
- Respects `school_year` setting for edit restrictions

## Notes for AI Agents

### Implementation Details
- **Simpler than Sattendance**: No subject mode, no notifications, no SMS integration
- **Batch Operations**: Uses `insert_batch_tattendance` and `update_batch_tattendance`
- **Self-Access**: `usertypeID == 2` users can view/print own attendance even without `tattendance_view`
- **Leave Date Calculation**: Same 60\*60\*24 iteration as Sattendance to generate date arrays

### Business Logic
- **Permission Model**: `tattendance` for marking, `tattendance_view` for viewing others
- **Teacher Filter**: No class/section filtering, shows all teachers in school
- **School ID Scoping**: All queries filtered by `schoolID` session variable

### Performance
- Simpler than Sattendance (no tag replacement, no email/SMS queues)
- Batch updates efficient for monthly saves
- Leave calculation could be slow for teachers with many leaves (nested loops)

