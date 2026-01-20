# Feature: Lmember

## Overview
**Controller**: `mvc/controllers/Lmember.php`  
**Primary Purpose**: Manages library membership for students, including registration, fees, and member ID assignment  
**User Roles**: Admin, Librarian, Student (view own profile)  
**Status**: ✅ Active

## Functionality
### Core Features
- Library member registration for students
- Unique library ID (lID) generation
- Library membership fee tracking (lbalance)
- Member profile viewing with class/section details
- Student selection by class
- PDF and email report generation for member cards
- Auto-population of member data from student records

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `lmember/index/{classID?}` | List students by class for membership | `lmember` |
| `add()` | `lmember/add/{studentID}/{classID}` | Add student as library member | `lmember_add` |
| `edit()` | `lmember/edit/{studentID}/{classID}` | Edit library member details | `lmember_edit` |
| `delete()` | `lmember/delete/{studentID}/{classID}` | Remove library membership | `lmember_delete` |
| `view()` | `lmember/view/{studentID}/{classID}` | View member profile | `lmember_view` |
| `print_preview()` | `lmember/print_preview/{studentID}/{classID}` | Generate PDF member card | `lmember_view` |
| `send_mail()` | `lmember/send_mail` | Email member card | `lmember_view` |
| `student_list()` | `lmember/student_list` | AJAX: Redirect to class list | - |

## Data Layer
### Models Used
- `lmember_m` - Library member operations
- `student_m` - Student master data
- `studentrelation_m` - Student-school year relations
- `section_m` - Section information
- `parents_m` - Parent data
- `studentgroup_m` - Student group data
- `subject_m` - Subject information

### Database Tables
- `lmember` - Library member records:
  - `lmemberID` (PK)
  - `lID` - Unique library member ID
  - `studentID` - Student reference
  - `name` - Member name (copied from student)
  - `email` - Member email (copied from student)
  - `phone` - Member phone (copied from student)
  - `lbalance` - Library membership fee
  - `ljoindate` - Membership join date (set to today)
  - `schoolID` - School identifier
- `student` - Student master table (`library` flag updated)
- `studentrelation` - Student-year relations

## Validation Rules
1. **lID**: Required, max 40 chars, XSS clean, must be unique within school
2. **lbalance**: Required, max 20 chars, numeric, must be >= 0

## Dependencies & Interconnections
### Depends On (Upstream)
- `Student` / `Studentrelation` - Student data and eligibility
- `Classes` - Class organization
- `Section` - Section organization

### Used By (Downstream)
- `Issue` - Library members can borrow books

### Related Features
- **Student**: Source of library members
- **Issue**: Book borrowing by members
- **Classes**: Organization structure

## User Flows
### Primary Flow: Add Library Member
1. Admin selects class from dropdown in index
2. System displays students in that class
3. Admin clicks "Add to Library" for a student
4. System generates lID (auto-increment or YEAR01 format)
5. System pre-populates name, email, phone from student record
6. Admin enters membership fee (lbalance)
7. System sets ljoindate to today
8. System updates student.library flag to 1
9. Member created, redirect to class index

### Library ID Generation Logic
- If existing members: `lastID + 1`
- If no members: `YEAR01` (e.g., 202401 for first member in 2024)
- Auto-suggested but can be edited by admin

### Secondary Flow: Edit Member
1. Admin navigates to edit from index
2. System loads member data
3. Admin can modify lID and lbalance
4. System validates new lID is unique
5. Update saved, redirect to class index

### Delete Flow
1. Admin clicks delete for a member
2. System deletes lmember record
3. System sets student.library flag back to 0
4. Student can be re-added to library later

### Student View (My Profile)
1. Student with library access navigates to lmember
2. System detects usertypeID=3 and no lmember_view permission
3. Automatically redirects to view own profile
4. Shows library card with class, section, user type

## Edge Cases & Limitations
1. **Library ID Uniqueness**: Validated at school level, can be manually edited
2. **Fee Validation**: lbalance must be >= 0, no negative fees
3. **Student Eligibility**: Student must be in current school year to be added
4. **Class Required**: Both studentID and classID required in URLs
5. **School Year Lock**: Add/Edit/Delete restricted to current school year or superadmin
6. **Email Requirements**: Student must have email for email functionality
7. **Profile Access**: Students without lmember_view permission auto-redirected to own profile
8. **Deletion**: Doesn't check for active book issues before deleting member

## Configuration
- Language file: `mvc/language/{lang}/lmember_lang.php`
- PDF template: `views/lmember/print_preview.php`
- CSS for PDF: `lmembermodule.css`

## Notes for AI Agents
- **Library Flag**: Critical to maintain `student.library` flag (1=member, 0=not member)
- **lID Format**: Flexible - can be auto-generated or manually entered, must be unique
- **Fee Tracking**: lbalance is membership fee, not related to book fines (those are in Invoice)
- **Student Data Sync**: Name, email, phone copied from student record at creation, not auto-updated
- **Class-Based Navigation**: Index requires class selection first, shows all students in class with library status
- **Section Support**: Displays students by section within class
- **My Profile**: Students without view permission see their own profile automatically
- **Delete Safety**: Consider checking for active book issues before allowing member deletion
- **School Year Restriction**: Most operations locked to current year to prevent historical data modification
