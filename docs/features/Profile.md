# Feature: Profile

## Overview
**Controller**: `mvc/controllers/Profile.php`  
**Primary Purpose**: Unified profile viewing across all user types (systemadmin, teacher, student, parent, staff) with role-specific data display.  
**User Roles**: All authenticated users (view own profile)  
**Status**: ✅ Active

## Functionality

### Core Features
- Unified profile view for all 5 user types
- Role-specific data sections (attendance, salary, marks, invoices, etc.)
- PDF generation for profile reports
- Email profile reports
- Document viewing
- Leave application history
- Parent: View children's information

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /profile/index | Display own profile based on usertypeID | Authenticated user |
| GET | /profile/print_preview | Generate PDF profile report | Own profile |
| POST | /profile/send_mail | Email PDF profile report | Own profile |

## Data Layer

### Models Used
- `systemadmin_m`, `teacher_m`, `student_m`, `parents_m`, `user_m`: User data by type
- `studentrelation_m`: Student-class-section relationships
- `studentgroup_m`: Student group memberships
- `manage_salary_m`, `salary_template_m`, `salaryoption_m`, `hourly_template_m`: Salary info (teachers/staff)
- `uattendance_m`, `tattendance_m`, `sattendance_m`, `subjectattendance_m`: Attendance records
- `make_payment_m`, `payment_m`: Payment history
- `routine_m`, `subject_m`: Schedule and subjects
- `invoice_m`, `weaverandfine_m`, `feetypes_m`: Invoice and fees (students)
- `exam_m`, `grade_m`, `mark_m`, `markpercentage_m`, `marksetting_m`: Exam marks (students/teachers)
- `document_m`: Attached documents
- `leaveapplication_m`: Leave requests

### Database Tables
Queries vary by usertypeID:
- usertypeID = 0: systemadmin table
- usertypeID = 1: teacher table
- usertypeID = 2: student table (via studentrelation)
- usertypeID = 3: parents table
- usertypeID >= 4: user table

## Validation Rules
Email sending:
- **to**: required, max_length[60], valid_email, xss_clean
- **subject**: required, xss_clean
- **message**: xss_clean

## Dependencies & Interconnections

### Depends On (Upstream)
- **All User Types**: Fetches data from respective user table
- **Schoolyear**: Data scoped to defaultschoolyearID

### Used By (Downstream)  
- **Dashboard**: May link to profile
- **Navigation**: Profile link in user menu

### Related Features
- **User/Teacher/Student/Parents/Systemadmin**: Data sources for profiles
- **Attendance**: Displayed in profile
- **Salary**: Displayed for teachers/staff
- **Invoice/Payment**: Displayed for students
- **Mark/Exam**: Displayed for students

## User Flows

### Primary Flow: View Own Profile
1. User clicks "Profile" in navigation
2. System identifies usertypeID from session
3. System fetches user record from appropriate table
4. System loads role-specific data:
   - **Systemadmin**: Basic info only
   - **Teacher**: Basic info + attendance + salary + payment + documents + leave applications
   - **Student**: Basic info + parent info + attendance + marks + invoices + payments + documents + routine + leave applications
   - **Parent**: Basic info + children info
   - **Staff (User)**: Basic info + attendance + salary + payment + documents + leave applications
5. Displays tabbed interface with sections
6. User can generate PDF or email report

### Primary Flow: Email Profile Report
1. User clicks "Email Profile" button
2. Modal appears with to/subject/message fields
3. User enters recipient email
4. System generates PDF report
5. System emails PDF as attachment with custom message
6. Returns JSON success/error response

## Edge Cases & Limitations
- Parent profile shows children but limited detail
- Salary calculation complex (basic + options - deductions)
- Attendance displayed by monthyear (plucked array)
- PDF generation may timeout for students with extensive data
- No profile editing (must use respective controller)

## Configuration
- Header assets: jquery.mCustomScrollbar for long profiles
- PDF CSS: profilemodule.css (for student/teacher), usermodule.css (for user)

## Notes for AI Agents
- **Unified Controller**: Single controller handles all 5 user types via conditional logic
- **Role Detection**: usertypeID determines which table to query and what data to display
- **Salary Complexity**: Calculates gross salary = basic_salary + sum(option_type 1) - sum(option_type 2)
- **Attendance Format**: Data plucked by monthyear for efficient lookup
- **Student Profile Extensive**: Students have most data (marks, invoices, payments, attendance, routine, parents, documents, leave)
- **PDF Reports**: Uses reportPDF() helper with role-specific CSS
- **Email**: Uses reportSendToMail() helper
- **Leave Applications**: Uses leave_applications_date_list_by_user_and_schoolyear() private method
- **Holiday Integration**: getHolidaysSession() and getWeekendDaysSession() for attendance context
