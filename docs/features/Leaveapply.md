# Feature: Leaveapply

## Overview
**Controller**: `mvc/controllers/Leaveapply.php`  
**Primary Purpose**: Allows users to submit, edit, and manage their own leave applications with attachment support and available leave balance checking.  
**User Roles**: All users (Teachers, Staff, Students, Parents)  
**Status**: ✅ Active

## Functionality

### Core Features
- Submit new leave applications with date range selection
- Upload supporting documents (attachments)
- View own submitted applications with approval status
- Edit pending applications before approval
- Delete pending (unapproved) applications
- View available leave balance by category
- Select approver from eligible users by role
- PDF generation and email distribution of applications

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `leaveapply/index` | List own leave applications | `leaveapply` |
| `add()` | `leaveapply/add` | Submit new leave application | `leaveapply` |
| `edit()` | `leaveapply/edit/{leaveapplicationID}` | Edit pending application | `leaveapply` |
| `delete()` | `leaveapply/delete/{leaveapplicationID}` | Delete pending application | `leaveapply` |
| `view()` | `leaveapply/view/{leaveapplicationID}` | View own application details | `leaveapply` |
| `print_preview()` | `leaveapply/print_preview/{leaveapplicationID}` | Generate PDF | `leaveapply_view` |
| `send_mail()` | AJAX POST | Email application PDF | `leaveapply_view` |
| `download()` | `leaveapply/download/{leaveapplicationID}` | Download own attachment | `leaveapply` |
| `usercall()` | AJAX POST | Get users by role (for approver selection) | - |

## Data Layer

### Models Used
- `leaveapplication_m`: CRUD and calculations
- `leavecategory_m`: Leave categories with quotas
- `leaveassign_m`: Allocated days per category/usertype/year
- `usertype_m`, `systemadmin_m`, `teacher_m`, `student_m`, `parents_m`, `user_m`: User data by type
- `studentrelation_m`: For student info with class/roll

### Database Tables
- `leaveapplication`: Application records (same structure as Leaveapplication feature)
- `leavecategory`: Sick, casual, annual, etc.
- `leaveassign`: Quota definitions

## Validation Rules

### Application Rules
- **applicationto_usertypeID**: Required, numeric, not '0'
- **applicationto_userID**: Required, numeric, not '0'
- **leavecategoryID**: Required, numeric, not '0'
- **leave_schedule**: Required, must be exactly 23 chars (MM/DD/YYYY - MM/DD/YYYY), valid dates
- **reason**: Required, max 10000 chars
- **attachment**: Optional, max 200 chars, callback validates upload

### Attachment Upload
- **Allowed types**: gif, jpg, png, jpeg, pdf, doc, docx, xml, xls, xlsx, txt, ppt, csv
- **Max size**: 1024KB (1MB)
- **Max dimensions**: 3000x3000 (for images)
- **Filename**: SHA-512 hash + extension
- **Storage**: `uploads/images/`

## Dependencies & Interconnections

### Depends On (Upstream)
- **Leavecategory**: Defines leave types
- **Leaveassign**: Sets available days per category
- **Usertype**: For approver role selection
- **User tables**: For approver dropdowns

### Used By (Downstream)
- **Leaveapplication**: Manages applications submitted via this feature
- **Attendance Features**: Integrate approved leaves
- **Reports**: Leave application reports

### Related Features
- **Leaveapplication**: Approval side (receives applications)
- **Leaveassign**: Quota management
- **Leavecategory**: Category configuration

## User Flows

### Primary Flow: Submit Leave Application
1. User navigates to Leaveapply → Add
2. System displays available leave categories with remaining days
3. User selects:
   - Approver role (dropdown of roles with `leaveapplication` permission)
   - Specific approver (filtered to exclude self)
   - Leave category
   - Date range (daterangepicker UI)
   - Reason (rich text editor)
   - Optional attachment
4. System validates dates, calculates leave days (excluding holidays/weekends)
5. Creates application record with `status=NULL` (pending)
6. Notification could be sent to approver (not implemented in controller)
7. Redirects to index

### Edit Flow
1. User views own applications in index
2. Clicks edit on pending (status=NULL) application
3. System loads application if owned and not yet approved/rejected
4. User modifies fields (approver, category, dates, reason, attachment)
5. Recalculates leave days
6. Updates record, resets `status=NULL`
7. Redirects to index

### Balance Check
1. On add/edit page load, system:
   - Queries all approved applications for user by category
   - Sums `leave_days` for each category
   - Subtracts from `leaveassign.leaveassignday`
   - Displays remaining balance
2. Helps user avoid over-applying

## Edge Cases & Limitations
- **Status Lock**: Can only edit/delete if `status=NULL` (pending)
- **Self-Exclusion**: Cannot select self as approver (filtered in `usercall()`)
- **Date Format**: Frontend uses MM/DD/YYYY (US format), backend stores YYYY-MM-DD
- **Leave Day Calculation**: Same holiday/weekend logic as Leaveapplication
- **Attachment Replacement**: Editing uploads new file, old file not auto-deleted
- **No Draft Mode**: All submissions are "pending" (no save-as-draft)
- **Approver Validation**: No check if selected approver has `leaveapplication` permission (frontend relies on role check)
- **School Year Lock**: Can only add/edit for current year (except superadmin)
- **File Cleanup**: Delete removes attachment file only if `config_item('demo') == FALSE`

## Configuration
- Uses daterangepicker jQuery plugin
- Uses WYSIWYG editor (jquery-te) for reason field
- File upload via CodeIgniter's upload library
- Holiday/weekend data from session

## Notes for AI Agents

### Implementation Details
- **Date Parsing**: `explode('-', $leave_schedule)` splits daterangepicker output (MM/DD/YYYY - MM/DD/YYYY)
- **Filename Hashing**: Uses `hash('sha512', random19().'leaveapplication'.encryption_key)` for security
- **Balance Display**: Uses `get_join_leavecategory_and_leaveassign()` to join categories with assigned days
- **User Selection**: `getuserlistbyrole()` method queries correct table based on usertypeID
- **Attachment Callback**: `attachmentUpload()` populates `$this->upload_data['file']` array

### Business Logic
- **Permission**: `leaveapply` for own applications, `leaveapply_view` for view/PDF/email
- **Ownership Check**: All CRUD operations validate `create_userID` and `create_usertypeID` match session
- **Status Reset**: Edit always sets `status=NULL` (re-approval required)
- **OD Status**: Optional `od_status` field (on-duty status, not well-documented)

### Performance
- **Real-Time Balance**: Recalculated on every page load (no caching)
- **User Dropdowns**: AJAX `usercall()` fetches full user list by role (could be slow for large schools)
- **Multiple Models**: Loads 8 different user models in constructor (lazy loading would help)

### Common Pitfalls
- **Date Validation**: `date_schedule_valid()` is complex - expects exact 23-char format
- **Student Info**: Uses `studentrelation` with prefix `sr` (name→srname, roll→srroll)
- **Parent Table**: Uses `parentsID`, not `parentID` (note the 's')
- **Attachment Path**: Stored in `uploads/images/`, not `uploads/leaveapplications/` (inconsistent with delete path check)
