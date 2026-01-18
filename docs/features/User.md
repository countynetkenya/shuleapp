# Feature: User

## Overview
**Controller**: `mvc/controllers/User.php`  
**Primary Purpose**: Manages staff/admin users (non-student, non-parent, non-teacher); handles CRUD, document uploads, salary info, attendance tracking, and email functionality.  
**User Roles**: Admin (usertypeID >= 5, excluding 1-4 which are Student/Admin/Teacher/Parent)  
**Status**: ✅ Active

## Functionality

### Core Features
- CRUD for staff users (usertypes 5+)
- Photo upload with hashed filenames
- Document attachment management (upload/download/delete)
- Salary information display (salary templates and hourly rates)
- Attendance tracking integration
- Payment history
- Email PDF reports
- Multi-school assignment
- Active/inactive toggle
- Username uniqueness across all user tables

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /user/index | List users or show profile for usertypes 5+ | user_view OR own profile |
| GET | /user/add | Display add user form | user_add |
| POST | /user/add | Create new user | user_add |
| GET | /user/edit/{id} | Display edit user form | user_edit |
| POST | /user/edit/{id} | Update user | user_edit |
| GET | /user/delete/{id} | Delete user and photo | user_delete |
| GET | /user/view/{id} | View detailed user profile | user_view OR own profile |
| POST | /user/documentUpload | AJAX upload document | user_add |
| GET | /user/download_document/{docID}/{userID}/{usertypeID} | Download document | user_add + user_delete OR own doc |
| GET | /user/delete_document/{docID}/{userID}/{usertypeID} | Delete document | user_add + user_delete |
| GET | /user/print_preview/{id} | Generate PDF report | user_view OR own profile |
| POST | /user/send_mail | Email PDF report | user_view OR own profile |
| POST | /user/active | Toggle user active status | user_edit |

## Data Layer

### Models Used
- `user_m`: User CRUD and authentication
- `usertype_m`: User role definitions
- `document_m`: Document attachments
- `uattendance_m`: User attendance records
- `manage_salary_m`: Salary assignments
- `salaryoption_m`: Salary template options
- `salary_template_m`: Salary templates
- `hourly_template_m`: Hourly rate templates
- `make_payment_m`: Payment records
- `leaveapplication_m`: Leave applications
- `systemadmin_m`: Systemadmin validation for multi-school

### Database Tables
- `user`: userID (PK), name, dob, sex, religion, email, phone, address, jod (join date), username, password (hashed), usertypeID, photo, schoolID (comma-separated), active, create_date, modify_date, create_userID, create_username, create_usertype
- `document`: documentID (PK), title, file, userID, usertypeID, create_date, create_userID, create_usertypeID, schoolID
- `uattendance`: User attendance records by monthyear
- `manage_salary`: Links users to salary/hourly templates
- `make_payment`: Payment transactions

## Validation Rules
- **name**: required, max_length[60], xss_clean
- **dob**: required, max_length[10], callback_date_valid (dd-mm-yyyy), xss_clean
- **sex**: max_length[10], xss_clean
- **religion**: max_length[25], xss_clean
- **email**: required, max_length[40], valid_email, xss_clean, callback_unique_email (checks across student/parents/teacher/user/systemadmin tables)
- **phone**: min_length[5], max_length[25], xss_clean
- **address**: max_length[200], xss_clean
- **jod**: required, max_length[10], callback_date_valid, xss_clean
- **usertypeID**: required, max_length[11], numeric, callback_unique_usertypeID (blocks 1-4)
- **photo**: max_length[200], callback_photoupload (gif/jpg/png, max 1024KB, 3000x3000px)
- **username**: required, min_length[4], max_length[40], xss_clean, callback_lol_username (unique across all user tables)
- **password**: required, min_length[4], max_length[40], xss_clean
- **schoolID[]**: required, callback_valid_schoolID (must exist in systemadmin's schools)

## Dependencies & Interconnections

### Depends On (Upstream)
- **Usertype**: usertypeID references usertype table
- **School**: Multi-school support requires school assignment
- **Systemadmin**: School validation checks systemadmin's schoolID

### Used By (Downstream)  
- **Attendance (Uattendance)**: Tracks user attendance
- **Make_payment**: Records salary payments
- **Document**: Stores user documents
- **Leaveapplication**: Leave requests reference user

### Related Features
- **Teacher**: Similar structure but different usertype
- **Student**: Similar profile view pattern
- **Parents**: Similar username/email uniqueness checks
- **Manage_salary**: Salary template assignment
- **Profile**: Users can view own profile

## User Flows

### Primary Flow: Create User
1. Admin navigates to /user/add
2. Fills form (name, dob, email, username, password, usertype, schools)
3. Uploads optional photo
4. System validates username/email uniqueness across ALL user tables
5. Password hashed via user_m->hash()
6. Creates user with active=1
7. Sends welcome email with credentials
8. Redirects to /user/index

### Primary Flow: View User Profile
1. User clicks on user in listing
2. System loads: basic info, attendance (by monthyear), salary template with calculated gross/net, payment history, documents, leave applications
3. Displays tabs for Attendance, Salary, Payment, Documents
4. Can generate PDF or email report

## Edge Cases & Limitations
- usertypeID 1-4 (Admin, Student, Teacher, Parent) blocked from User controller
- Username/email must be unique across 5 tables: student, parents, teacher, user, systemadmin
- Photo deletion only if not default.png/defualt.png (typo in code)
- Multi-school users have comma-separated schoolID field (not relational)
- Salary calculation complex: basic_salary + options (type 1) - options (type 2)
- Document upload max 5120KB (5MB), allows: gif/jpg/png/jpeg/pdf/doc/xml/docx/xls/xlsx/txt/ppt/csv

## Configuration
- Photo upload: uploads/images, max 1MB, 3000x3000px
- Document upload: uploads/documents, max 5MB
- Default photo: default.png
- Email sending: Uses reportSendToMail() helper

## Notes for AI Agents
- **Username Collision**: lol_username callback checks 5 tables; performance impact on large datasets
- **Password Hashing**: Uses user_m->hash() (check implementation for algorithm)
- **Photo Naming**: SHA-512 hash of random19() + username + encryption_key
- **Multi-School Storage**: Schools stored as comma-separated string in schoolID field; query optimization needed
- **Salary Complexity**: Supports both salary templates (monthly) and hourly templates; calculation in view layer
- **Permission Granularity**: Different permissions for add/edit/delete/view; document access requires user_add + user_delete
- **Active Toggle**: AJAX endpoint for enabling/disabling user accounts without full edit
- **Leave Applications**: Integrated display but managed by leaveapplication controller
- **Email Functionality**: Can email PDF reports with custom message

