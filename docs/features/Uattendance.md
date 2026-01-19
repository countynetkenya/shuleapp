# Feature: Uattendance (User Attendance)

## Overview
**Controller**: `mvc/controllers/Uattendance.php`  
**Primary Purpose**: Manages daily attendance for non-student, non-teacher users (e.g., librarians, accountants, custom roles).  
**User Roles**: Admins, HR Staff, Superadmin (Users can view own attendance)  
**Status**: ✅ Active

## Functionality

### Core Features
- Monthly attendance tracking for custom user types
- Excludes students (type 3), teachers (type 2), parents (type 4), and superadmins (type 1)
- Bulk attendance marking by date
- Leave application integration for all user types
- PDF reports and email distribution

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `uattendance/index` | List users or redirect to own attendance | `uattendance_view` or self |
| `add()` | `uattendance/add` | Mark attendance for specific date | `uattendance` |
| `save_attendace()` | AJAX POST | Save user attendance data | `uattendance` |
| `view()` | `uattendance/view/{userID}` | View user attendance history | `uattendance_view` or self |
| `print_preview()` | `uattendance/print_preview/{userID}` | Generate PDF attendance report | `uattendance_view` or self |
| `send_mail()` | AJAX POST | Email attendance report | `uattendance_view` or self |

## Data Layer

### Models Used
- `uattendance_m`: User attendance records
- `user_m`: User information (custom roles)
- `usertype_m`: User type definitions
- `leaveapplication_m`: Approved user leave applications

### Database Tables
- `uattendance`: `uattendanceID`, `userID`, `usertypeID`, `monthyear`, `a1-a31` (P/A/L/H), `schoolyearID`, `schoolID`
- `user`: User details (non-students/teachers)
- `usertype`: Role definitions
- `leaveapplication`: For approved leaves

## Validation Rules

### Add Attendance Rules
- **date**: Required, dd-mm-yyyy format, not future, not holiday, not weekend, within school year

### Save Attendance Rules
- **day**: Required, numeric (1-31)
- **monthyear**: Required (MM-YYYY)
- **attendance[]**: Required array of P/A status

### Send Mail Rules
- **id**: Required user ID, numeric
- **to**: Required, valid email
- **subject**: Required
- **message**: Optional

## Dependencies & Interconnections

### Depends On (Upstream)
- **User**: Requires user records (custom roles)
- **Usertype**: For role filtering
- **Holiday**: For date validation
- **Schoolyear**: For academic year context
- **Leaveapplication**: For marking approved user leaves

### Used By (Downstream)
- **Salaryreport**: Attendance may affect salary calculations
- **HR Reports**: Staff attendance analytics
- **Leaveapplicationreport**: Cross-reference with leave data

### Related Features
- **Tattendance**: Teacher attendance (similar structure)
- **Sattendance**: Student attendance
- **Leaveapplication**: Leave tracking for all users
- **User**: User management

## User Flows

### Primary Flow: Mark User Attendance
1. Admin/HR navigates to Uattendance → Add
2. Selects date (validates not holiday/weekend/future)
3. System displays all eligible users (excluding students/teachers/parents/superadmins)
4. Auto-creates monthly records if first time for this month
5. Marks P (Present) or A (Absent) for each user
6. Saves via AJAX, updates database
7. Returns success/error status

### View Flow
1. Navigate to Uattendance index
2. System lists all non-excluded users
3. Click user name or view own attendance
4. Displays monthly calendars with P/A/L markers
5. Leave applications shown as 'L'
6. Options to print PDF or email report

## Edge Cases & Limitations
- **User Type Filtering**: Explicitly excludes types 1 (superadmin), 2 (teacher), 3 (student), 4 (parent)
- **Dynamic User List**: Uses `get_user_by_usertype` which filters excluded types
- **Same Structure as Tattendance**: Monthly a1-a31 columns
- **Self-Access**: Users can view own attendance if they lack `uattendance_view` permission
- **School Year Lock**: Can only mark for current year (except superadmin)
- **Leave Integration**: Visual overlay only (not stored in attendance)

## Configuration
- Uses global holiday and weekend settings from session
- Filtered by `schoolID` and `schoolyearID`
- Respects `school_year` setting for edit lock

## Notes for AI Agents

### Implementation Details
- **User Type Exclusion**: Constructor doesn't filter, but `index()` and `add()` use `get_user_by_usertype` which excludes types 1-4
- **Pluck Operation**: Uses `pluck($usertype_m->get_usertype(), 'usertype', 'usertypeID')` then `unset` to remove excluded types
- **Leave Calculation**: Same date array generation as other attendance features
- **Permission Model**: `uattendance` for marking, `uattendance_view` for viewing others

### Business Logic
- **usertype_m->get_order_by_usertype_with_or**: Returns all types including school-specific custom roles
- **User Table vs Teacher Table**: Queries `user` table, not `teacher` or `student`
- **School-Scoped**: All user types filtered by `schoolID` (multi-tenant safe)

### Performance
- Simpler than Sattendance (no notifications, no complex templates)
- Batch operations for efficiency
- Leave date loops could be slow for users with many leaves

