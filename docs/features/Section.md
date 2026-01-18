# Feature: Section

## Overview
**Controller**: `mvc/controllers/Section.php`  
**Primary Purpose**: Manages sections/streams within classes, allowing subdivision of large classes (e.g., Form 4A, Form 4B).  
**User Roles**: Admin  
**Status**: ✅ Active

## Functionality

### Core Features
- CRUD for sections within classes
- Assign section teacher to each section
- Define section capacity (student limit)
- Categorize sections (e.g., Science, Arts)
- Class-scoped section listing

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /section/index | Display section selector (no class selected) | Admin_Controller |
| GET | /section/index/{classesID} | List sections for specific class | Admin_Controller |
| GET | /section/add | Display add section form | Admin_Controller |
| POST | /section/add | Create new section | Admin_Controller |
| GET | /section/edit/{id} | Display edit section form | Admin_Controller |
| POST | /section/edit/{id} | Update section and cascade to studentrelation | Admin_Controller |
| GET | /section/delete/{id} | Delete section | Admin_Controller |

## Data Layer

### Models Used
- `section_m`: Section CRUD operations
- `classes_m`: Class selection and validation
- `teacher_m`: Teacher selection for section teacher
- `studentrelation_m`: Update student relations on section name change

### Database Tables
- `section`: sectionID (PK), section (name), category, capacity, classesID, teacherID, note, create_date, modify_date, create_userID, create_username, create_usertype, schoolID

## Validation Rules
- **section**: required, max_length[60], xss_clean, callback_unique_section (unique per school)
- **category**: required, max_length[128], xss_clean (e.g., "Science", "Arts", "General")
- **capacity**: required, max_length[11], numeric, callback_valid_number (student limit)
- **classesID**: required, numeric, max_length[11], xss_clean, callback_unique_classes (must exist)
- **teacherID**: required, numeric, max_length[11], xss_clean, callback_unique_teacher (must exist)
- **note**: max_length[200], xss_clean

## Dependencies & Interconnections

### Depends On (Upstream)
- **Classes**: sectionID references classesID
- **Teacher**: teacherID references teacher table (section teacher)

### Used By (Downstream)  
- **Student**: Students assigned to sections via studentrelation
- **Routine**: Section-specific timetables
- **Attendance**: Attendance tracked per section

### Related Features
- **Classes**: Sections subdivide classes
- **Student**: Students enrolled in specific sections
- **Subject**: Subjects may be section-specific

## User Flows

### Primary Flow: Create Section
1. Admin selects class from dropdown
2. Navigates to /section/add
3. Enters section name (e.g., "A", "B", "Science Stream")
4. Sets category (Science/Arts/General)
5. Defines capacity (max students)
6. Assigns section teacher
7. System creates section record
8. Redirects to /section/index/{classesID}

### Primary Flow: Edit Section
1. Admin edits section name or capacity
2. System updates section table
3. System cascades update to studentrelation.srsection (denormalized)
4. Ensures data consistency

## Edge Cases & Limitations
- capacity field defined but enforcement not visible in controller
- Deleting section doesn't check for students enrolled
- Section names must be unique per school (not per class) - may cause conflicts
- Students can switch sections but history not tracked

## Configuration
- None specific to sections

## Notes for AI Agents
- **Capacity**: capacity field exists but not enforced in code
- **Cascade Update**: Edit cascades to studentrelation.srsection
- **Unique Constraint**: Section names unique per school (not per class); "A" can only exist once
- **Section Teacher**: teacherID is the section teacher assignment
- **Category**: Free-text field for section categorization (Science/Arts/etc.)
- **UI Pattern**: Uses class selector dropdown to filter sections
- **Header Assets**: Uses select2 for dropdowns
