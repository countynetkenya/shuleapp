# Feature: Mark

## Overview
**Controller**: `mvc/controllers/Mark.php`  
**Primary Purpose**: Manage student marks/grades for exams across subjects. Handles mark entry, calculation, viewing, and reporting with support for multiple mark percentage components.  
**User Roles**: Admin, Teachers, Students (view only)  
**Status**: ✅ Active - Critical academic feature

## Functionality
### Core Features
- Enter marks for students by exam, class, section, and subject
- Support multiple mark percentage components (e.g., Midterm 40%, Final 60%)
- View student mark sheets with calculated totals and highest marks
- Generate PDF mark reports
- Email mark reports to parents/students
- Calculate weighted marks based on marksetting configurations
- Support optional subjects and subject type filtering
- Track teacher comments on performance

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `/mark/index/{classesID}` | List students by class for mark entry | Standard |
| `add()` | `/mark/add` | Enter marks for exam/class/subject combo | School year check |
| `view()` | `/mark/view/{studentID}/{classID}` | View student mark sheet | Standard |
| `mark_send()` | AJAX POST | Submit marks for multiple students | School year check |
| `print_preview()` | `/mark/print_preview/{studentID}/{classID}` | Generate PDF mark report | Permission check |
| `send_mail()` | AJAX POST | Email mark report to recipient | Permission check |
| `mark_list()` | AJAX POST | Redirect to class mark listing | Internal |
| `examcall()` | AJAX POST | Load exams for selected class | AJAX |
| `subjectcall()` | AJAX POST | Load subjects for selected class | AJAX |
| `sectioncall()` | AJAX POST | Load sections for selected class | AJAX |

## Data Layer
### Models Used
- `mark_m`: CRUD operations for marks table
- `grade_m`: Get grade definitions for conversion
- `classes_m`: Load class information
- `exam_m`: Load exam definitions
- `subject_m`: Load subject configurations
- `section_m`: Load section data
- `student_m`: Get student details
- `markrelation_m`: Link marks to percentage components
- `markpercentage_m`: Get percentage type definitions
- `studentrelation_m`: Get student-class-year relationships
- `marksetting_m`: Get mark configuration by class/exam/subject

### Database Tables
- `mark`: Main marks table
  - `markID` (PK)
  - `examID` (FK to exam)
  - `classesID` (FK to classes)
  - `subjectID` (FK to subject)
  - `studentID` (FK to student)
  - `schoolyearID` (FK to schoolyear)
  - `schoolID` (FK to school)
  - `teacher_comment` (text, max 140 chars)
  - `exam`, `subject`, `year` (denormalized for performance)
  - `create_date`, `create_userID`, `create_usertypeID` (audit)
- `markrelation`: Links marks to percentage components
  - `markrelationID` (PK)
  - `markID` (FK to mark)
  - `markpercentageID` (FK to markpercentage)
  - `mark` (numeric value for this component)
  - `schoolID` (FK)
- `markpercentage`: Defines mark components (e.g., Midterm, Final)
- `marksetting`: Configures which percentages apply to which exam/class/subject

## Validation Rules
### Add/Entry Rules
- **examID**: required, numeric, must be valid (callback: unique_examID)
- **classesID**: required, numeric, must be valid (callback: unique_classesID)
- **sectionID**: required, numeric, must be valid (callback: unique_sectionID)
- **subjectID**: required, numeric, must be valid (callback: unique_subjectID)

### Mark Submission Rules
- **inputs**: array of marks, validated against max marks per percentage (callback: unique_inputs)
- **comments**: array of teacher comments, max 140 chars each (callback: unique_comments)
- Mark values must not exceed configured percentage max

### Email Rules
- **to**: required, valid email, max 60 chars
- **subject**: required, text
- **message**: optional, text
- **id** (studentID): required, numeric, max 10 chars
- **set** (classesID): required, numeric, max 10 chars

## Dependencies & Interconnections
### Depends On (Upstream)
- **Exam**: Marks are entered per exam
- **Classes**: Marks organized by class level
- **Section**: Marks filtered by class section
- **Subject**: Marks entered per subject
- **Student**: Marks assigned to students
- **Schoolyear**: Marks scoped to academic year
- **Markpercentage**: Defines mark components (Midterm, Final, etc.)
- **Marksetting**: Configures which percentages apply where
- **Markrelation**: Links individual marks to percentage components
- **Grade**: Converts numeric marks to letter grades

### Used By (Downstream)  
- **Marksheetreport**: Generates student report cards
- **Studentexamreport**: Shows exam performance
- **Examranking**: Ranks students by total marks
- **Examcompilation**: Compiles results across exams
- **Tabulationsheet**: Tabulated mark displays
- **Progresscardreport**: Shows academic progress

### Related Features
- **Promotion**: Uses marks to determine student promotion
- **Gradingsystem**: Applies grades to numeric marks
- **Teacher**: Teachers enter and manage marks

## User Flows
### Primary Flow: Enter Marks for Class
1. Navigate to `/mark/add`
2. Select exam, class, section, and subject
3. System loads all students in that section
4. System creates mark and markrelation records if not exist
5. For each student, display input fields for each mark percentage (e.g., Midterm, Final)
6. Teacher enters marks (validated against max per percentage)
7. Optionally add teacher comment (max 140 chars)
8. Submit via AJAX (`mark_send`)
9. System updates mark and markrelation tables
10. Display success message

### Primary Flow: View Student Marks
1. Navigate to class student listing
2. Click student name to view marks
3. System loads all marks for student across all exams
4. Display table showing:
   - Each exam as row
   - Each subject as column
   - Each percentage component as sub-column
   - Highest marks in class for comparison
5. Calculate and display totals

### Primary Flow: Generate PDF Report
1. View student marks
2. Click "Print/PDF" button
3. System generates PDF with:
   - Student info header
   - Mark table (exams × subjects × percentages)
   - Grade conversions
   - Highest marks comparison
4. Download PDF or print

### Primary Flow: Email Report
1. View student marks
2. Click "Email" button
3. Enter recipient email, subject, message
4. System generates PDF, attaches to email
5. Send via configured mail system
6. Display success confirmation

## Edge Cases & Limitations
- **School Year Locking**: Can only enter marks for current school year (unless superadmin)
- **Missing Mark Relations**: System auto-creates markrelation records if missing when percentages are added
- **Subject Types**: Supports 3 types:
  - Type 1: Compulsory (all students)
  - Type 2: Non-examinable (selected students via srnonexaminablesubjectID)
  - Type 0: Optional (selected students via sroptionalsubjectID)
- **Student Filtering**: Only shows students enrolled in selected subject type
- **Graduated Classes**: Excludes `ex_class` (graduated class) from dropdown
- **Mark Validation**: Enforces max mark per percentage component
- **Comment Length**: Teacher comments limited to 140 characters
- **Denormalization**: exam and subject names stored in mark table for performance
- **Highest Marks**: Calculated across all students in same class/section

## Configuration
- **Required Settings**:
  - `marktypeID` in siteinfos: Determines mark calculation method
  - `school_year` in siteinfos: Current academic year
  - `ex_class` in siteinfos: Graduated class to exclude
- **Session Variables**:
  - `schoolID`: Current school context
  - `defaultschoolyearID`: Active academic year
  - `usertypeID`: User role (3 = student can view own marks)
  - `loginuserID`: Current user

## Notes for AI Agents
- **Most Complex Controller**: Mark.php is one of the largest (1040 lines), handles multi-dimensional data
- **Critical Performance Path**: Uses denormalized data (exam/subject names) to avoid joins
- **Batch Operations**: mark_send() updates multiple students' marks in one transaction
- **PDF Generation**: Uses reportPDF() helper from Admin_Controller
- **Email Integration**: Generates PDF, attaches to email via reportSendToMail()
- **Weighted Calculations**: Mark totals calculated based on percentage weights from marksetting
- **Auto-Creation**: Automatically creates mark and markrelation records when accessing add page
- **Missing Percentage Sync**: Detects and creates missing markrelation records for new percentages
- **Multi-Level Permissions**:
  - Teachers: Enter marks for their assigned classes
  - Students: View only their own marks
  - Admin: Full access to all marks
- **Grading Integration**: Marks converted to grades using grade_m ranges for display
- **Highest Mark Tracking**: Stores highest mark per exam/subject/percentage for comparison
- **AJAX-Heavy UI**: Most interactions use AJAX for dynamic loading (exams, subjects, sections)
- **Validation Callbacks**: 10+ custom validation methods for data integrity
- **Mark Components**: Supports any number of percentage components per exam/subject
- **Known Issue**: unique_comments() callback has logic error (foreach on undefined $comments)
- **Design Pattern**: Uses "pluck" pattern extensively for efficient data lookups
- **Optimization**: Batch insert_batch_mark() for creating multiple records efficiently

