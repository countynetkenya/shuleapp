# Feature: Subject

## Overview
**Controller**: `mvc/controllers/Subject.php`  
**Primary Purpose**: Manages subjects/courses taught in classes with teacher assignments, pass marks, and grading criteria.  
**User Roles**: Admin  
**Status**: ✅ Active

## Functionality

### Core Features
- CRUD for subjects per class
- Multi-teacher assignment per subject (subjectteacher table)
- Define pass mark, final mark, target mark per subject
- Subject types: Mandatory vs Optional
- Subject code for identification
- Optional author field (e.g., for textbooks)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /subject/index | Display subject selector (no class selected) | Admin_Controller |
| GET | /subject/index/{classesID} | List subjects for specific class | Admin_Controller |
| GET | /subject/add | Display add subject form | Admin_Controller |
| POST | /subject/add | Create new subject with teacher assignments | Admin_Controller |
| GET | /subject/edit/{id} | Display edit subject form | Admin_Controller |
| POST | /subject/edit/{id} | Update subject and teacher assignments | Admin_Controller |
| GET | /subject/delete/{id} | Delete subject and teacher relationships | Admin_Controller |

## Data Layer

### Models Used
- `subject_m`: Subject CRUD operations
- `classes_m`: Class selection and validation
- `teacher_m`: Teacher selection for subject teachers
- `subjectteacher_m`: Teacher-to-subject many-to-many relationships
- `student_m`: For class listing
- `studentrelation_m`: Student subject assignments

### Database Tables
- `subject`: subjectID (PK), subject (name), subject_code, subject_author, type (Mandatory/Optional), classesID, passmark, finalmark, targetmark, create_date, modify_date, create_userID, create_username, create_usertype, schoolID
- `subjectteacher`: subjectteacherID (PK), subjectID, teacherID, classesID (allows multiple teachers per subject)

## Validation Rules
- **subject**: required, max_length[60], xss_clean, callback_unique_subject (unique per class)
- **subject_code**: required, max_length[20], xss_clean, callback_unique_subject_code (unique per class)
- **subject_author**: max_length[100], xss_clean
- **type**: required, max_length[11], callback_unique_type (1=Mandatory, 2=Optional)
- **classesID**: required, numeric, max_length[11], xss_clean, callback_unique_classes (must exist)
- **teacherID**: callback_unique_teacher (validates multiple teachers)
- **passmark**: required, numeric, max_length[11], xss_clean
- **finalmark**: required, numeric, max_length[11], xss_clean
- **targetmark**: required, numeric, max_length[11], xss_clean

## Dependencies & Interconnections

### Depends On (Upstream)
- **Classes**: subjectID references classesID
- **Teacher**: Multiple teachers can teach same subject

### Used By (Downstream)  
- **Exam**: Exams administered per subject
- **Mark**: Marks recorded per subject
- **Routine**: Subject timetable scheduling
- **Attendance**: Subject-based attendance tracking

### Related Features
- **Classes**: Subjects taught in specific classes
- **Teacher**: Multi-teacher assignment via subjectteacher table
- **Exam**: Grading uses passmark, finalmark, targetmark
- **Mark**: Student marks per subject

## User Flows

### Primary Flow: Create Subject
1. Admin selects class from dropdown
2. Navigates to /subject/add
3. Enters subject name (e.g., "Mathematics"), code (e.g., "MATH101")
4. Sets type (Mandatory/Optional)
5. Defines pass mark (e.g., 40), final mark (e.g., 100), target mark (e.g., 75)
6. Selects one or more teachers to teach subject
7. System creates subject record
8. System inserts subjectteacher relationships for each selected teacher
9. Redirects to /subject/index/{classesID}

### Primary Flow: Edit Subject with Teacher Changes
1. Admin edits subject
2. Changes teacher assignments (add/remove teachers)
3. System deletes all existing subjectteacher relationships for this subject
4. System inserts new subjectteacher relationships
5. Updates subject record
6. Redirects to subject listing

## Edge Cases & Limitations
- Deleting subject doesn't check for existing marks or exams
- Subject names unique per class (not globally)
- subject_author field rarely used
- passmark, finalmark, targetmark are integers (no decimal marks)
- Teacher removal deletes all subjectteacher records and recreates (not differential update)

## Configuration
- Type values: 1 = Mandatory, 2 = Optional

## Notes for AI Agents
- **Multi-Teacher Support**: subjectteacher table allows multiple teachers per subject
- **Marks Configuration**: passmark (minimum to pass), finalmark (maximum possible), targetmark (expected average)
- **Delete-Insert Pattern**: Edit operation deletes all subjectteacher records and recreates them
- **Type Field**: 1 = Mandatory (all students must take), 2 = Optional (student choice)
- **Author Field**: Intended for textbook author but rarely used
- **Class Scoping**: Subjects scoped to classesID; same subject can exist in multiple classes with different settings
- **Header Assets**: Uses select2 for multi-select teacher dropdown
