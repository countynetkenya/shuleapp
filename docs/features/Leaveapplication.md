# Feature: Leaveapplication

## Overview
**Controller**: `mvc/controllers/Leaveapplication.php`  
**Primary Purpose**: Manages viewing and approval/rejection of leave applications submitted by staff, students, and other users.  
**User Roles**: Admins, Managers, Approvers (users who receive applications)  
**Status**: ✅ Active

## Functionality

### Core Features
- View leave applications received by logged-in user
- Approve or reject leave applications
- View detailed leave information with available days calculation
- PDF generation for leave application documents
- Email leave application reports
- Download leave application attachments
- Integrated with Leaveassign (allocated leave days) and Leavecategory

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `leaveapplication/index` | List received leave applications | `leaveapplication` |
| `view()` | `leaveapplication/view/{leaveapplicationID}` | View application details | `leaveapplication` |
| `status()` | `leaveapplication/status/{leaveapplicationID}` | Toggle approve/reject status | `leaveapplication` |
| `print_preview()` | `leaveapplication/print_preview/{leaveapplicationID}` | Generate PDF of application | `leaveapplication` |
| `send_mail()` | AJAX POST | Email leave application PDF | `leaveapplication` |
| `download()` | `leaveapplication/download/{leaveapplicationID}` | Download attachment file | `leaveapplication` |

## Data Layer

### Models Used
- `leaveapplication_m`: Leave application records and calculations
- `leavecategory_m`: Leave category (sick, casual, etc.)
- `leaveassign_m`: Allocated leave days per category/usertype
- `usertype_m`: User type information

### Database Tables
- `leaveapplication`: `leaveapplicationID`, `leavecategoryID`, `from_date`, `to_date`, `leave_days`, `reason`, `status` (NULL/0/1), `attachment`, `attachmentorginalname`, `create_userID`, `create_usertypeID`, `applicationto_userID`, `applicationto_usertypeID`, `schoolyearID`, `schoolID`, timestamps
- `leavecategory`: Category definitions
- `leaveassign`: Allocated days per category/usertype/year
- `usertype`: User role definitions

## Validation Rules

### Send Mail Rules
- **to**: Required, valid email, max 60
- **subject**: Required
- **message**: Optional
- **leaveapplicationID**: Required, must not be '0'

## Dependencies & Interconnections

### Depends On (Upstream)
- **Leaveapply**: Creates leave applications that this feature manages
- **Leaveassign**: Defines available leave days for calculation
- **Leavecategory**: Categorizes leave types
- **User/Teacher/Student**: Applicants and approvers
- **Schoolyear**: Leave tied to academic year

### Used By (Downstream)
- **Sattendance**: Marks approved leaves as 'L' in attendance
- **Tattendance**: Marks approved teacher leaves
- **Uattendance**: Marks approved user leaves
- **Leaveapplicationreport**: Generates leave reports
- **Salaryreport**: May deduct salary for unpaid leaves

### Related Features
- **Leaveapply**: Application submission (applicant side)
- **Leaveassign**: Leave quota allocation
- **Leavecategory**: Leave type management
- **Attendance Features**: Integrate approved leaves

## User Flows

### Primary Flow: Review Leave Application
1. Manager navigates to Leaveapplication index
2. System shows applications where `applicationto_userID` matches logged-in user
3. Manager clicks "View" on an application
4. System displays:
   - Applicant details
   - Leave category and dates
   - Calculated leave days (excluding holidays/weekends)
   - Available leave balance for category
   - Reason and attachment
5. Manager can approve/reject via status toggle
6. System updates `status` field (1=Approved, 0=Rejected, NULL=Pending)
7. Redirect back to index

### View Details Flow
1. View application (as above)
2. System calculates:
   - Total days from date range
   - Minus holidays (from session)
   - Minus weekends (from session)
   - Net leave days required
3. Retrieves leaveassign for category
4. Calculates: Assigned Days - Already Used = Available
5. Shows "Available Days" to help decision
6. Attachment download link if present

### Download Attachment
1. Click download link on view page
2. System validates application belongs to user
3. Retrieves file from `uploads/images/`
4. Forces browser download with original filename
5. Handles file not found errors

## Edge Cases & Limitations
- **Permission Model**: Users only see applications sent TO them (not all applications)
- **Superadmin View**: Type 1 users see ALL applications in school
- **Status Values**: NULL (pending), 0 (rejected), 1 (approved) - toggle switches between 0 and 1
- **Date Calculation**: Excludes holidays and weekends from leave day count
- **Leave Balance**: Calculated in real-time (not cached) - queries all approved leaves for category
- **Attachment Validation**: No file type or size validation on download
- **Single Approver**: No multi-level approval workflow
- **No Rejection Reason**: Status toggle doesn't capture why leave was rejected
- **Deleted Category**: If category deleted, shows "Deleted" message

## Configuration
- Uses holiday and weekend settings from session (`getHolidaysSession()`, `getWeekendDaysSession()`)
- Filtered by `schoolyearID` and `schoolID`
- File uploads stored in `uploads/images/`

## Notes for AI Agents

### Implementation Details
- **Leave Day Calculation**: `leavedayscustomCompute()` method iterates date range, checks each day against holidays/weekends
- **Balance Query**: `get_sum_of_leave_days_by_user_for_single_category()` sums all approved applications for user/category
- **Applicant Retrieval**: `getObjectByUserTypeIDAndUserID()` helper function (global) fetches user from correct table
- **Permission Check**: Both `applicationto_userID` match AND permission required (or superadmin)
- **PDF Generation**: Uses `reportPDF()` base controller method with 'leaveapplicationmodule.css'

### Business Logic
- **Status Toggle**: Clicking status switches 1→0 or 0→1 (NULL→1 on first approve)
- **School Year Restriction**: Edit allowed only if `school_year` setting matches or superadmin
- **Holiday Array Format**: Stored as quoted comma-separated values, exploded to array
- **Weekend Days**: Returns array of dd-mm-yyyy strings for weekend dates
- **File Security**: Download checks `applicationto_userID` to prevent unauthorized access

### Performance
- **Real-Time Balance**: Every view recalculates leave balance (no caching)
- **Date Iteration**: `get_day_using_two_date()` helper could be slow for long leaves
- **N+1 Queries**: Index page doesn't preload categories/usertypes (potential optimization)

### Common Pitfalls
- **Modify Date**: Always updated on status change (tracks last approval/rejection time)
- **Application To**: Must match logged-in user (except superadmin) - filters in query
- **School Year Lock**: Can't toggle status for past year applications (except superadmin)
- **Attachment Path**: Hardcoded to `uploads/images/` - not configurable

