# Feature: Classes

## Overview
**Controller**: `mvc/controllers/Classes.php`  
**Primary Purpose**: Manages class/grade levels in the school hierarchy; classes are the primary organizational unit for students (e.g., Grade 1, Form 4).  
**User Roles**: Admin  
**Status**: ✅ Active

## Functionality

### Core Features
- CRUD for class/grade definitions
- Assign class teacher to each class
- Link classes to divisions (e.g., Primary, Secondary)
- Store denormalized division name in classes table for performance
- Update student relations when class name changes

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /classes/index | List all classes for school | Admin_Controller |
| GET | /classes/add | Display add class form | Admin_Controller |
| POST | /classes/add | Create new class | Admin_Controller |
| GET | /classes/edit/{id} | Display edit class form | Admin_Controller |
| POST | /classes/edit/{id} | Update class and cascade to studentrelation | Admin_Controller |
| GET | /classes/delete/{id} | Delete class | Admin_Controller |

## Data Layer

### Models Used
- `classes_m`: Classes CRUD operations
- `teacher_m`: Teacher selection for class teacher
- `studentrelation_m`: Update student relations on class name change
- `divisions_m`: Division selection

### Database Tables
- `classes`: classesID (PK), classes (name), classes_code, divisionID, division (denormalized), teacherID, studentmaxID (default 999999999), note, create_date, modify_date, create_userID, create_username, create_usertype, schoolID

## Validation Rules
- **classes**: required, max_length[60], xss_clean, callback_unique_classes (unique per school)
- **classes_code**: required, alpha_numeric, max_length[11], xss_clean, callback_unique_classes_code (unique per school)
- **divisionID**: required, numeric, max_length[11], xss_clean, callback_unique_division (must exist)
- **teacherID**: required, numeric, max_length[11], xss_clean, callback_unique_teacher (must exist)
- **note**: max_length[200], xss_clean

## Dependencies & Interconnections

### Depends On (Upstream)
- **Division**: divisionID references divisions table
- **Teacher**: teacherID references teacher table (class teacher)

### Used By (Downstream)  
- **Section**: Sections belong to classes
- **Subject**: Subjects taught in classes
- **Student**: Students enrolled in classes via studentrelation
- **Routine**: Class timetables
- **Exam**: Exams administered per class
- **Invoice**: Fee invoices scoped to class

### Related Features
- **Section**: Classes contain sections (e.g., Class 5 has 5A, 5B)
- **Division**: Classes grouped by division (Primary/Secondary)
- **Student**: Primary organizational unit for students

## User Flows

### Primary Flow: Create Class
1. Admin navigates to /classes/add
2. Selects division (Primary/Secondary/etc.)
3. Enters class name (e.g., "Form 4"), code (e.g., "F4")
4. Assigns class teacher
5. System denormalizes division name into classes.division field
6. Sets studentmaxID to 999999999 (unlimited capacity)
7. Creates class record
8. Redirects to /classes/index

### Primary Flow: Edit Class
1. Admin edits class name
2. System updates classes table
3. System cascades update to studentrelation.srclasses (denormalized class name)
4. Ensures data consistency across relations

## Edge Cases & Limitations
- studentmaxID always set to 999999999 (capacity not enforced)
- Division name denormalized in classes.division for performance (risk of sync issues)
- Deleting class doesn't check for students enrolled
- classes_code must be alpha_numeric (no special characters)

## Configuration
- studentmaxID: Hard-coded to 999999999 (unlimited)

## Notes for AI Agents
- **Denormalization**: division field duplicates data from divisions table; updates to division name not cascaded
- **Cascade Update**: Editing class name updates studentrelation.srclasses (line 152)
- **Capacity**: studentmaxID exists but not enforced anywhere
- **Class Teacher**: teacherID is the primary class teacher assignment
- **Header Assets**: Uses select2 for dropdowns
