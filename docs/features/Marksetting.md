# Feature: Marksetting

## Overview
**Controller**: `mvc/controllers/Marksetting.php`  
**Primary Purpose**: Configure how marks are calculated across the system. Defines which mark percentage components (Midterm, Final, Quiz, etc.) apply to which combinations of exams, classes, and subjects. Supports 7 different marking schemes.  
**User Roles**: Admin, Academic coordinators  
**Status**: ✅ Active - System configuration feature

## Functionality
### Core Features
- Configure mark calculation methods (7 types: Global, Class-wise, Exam-wise, etc.)
- Assign mark percentage components to exam/class/subject combinations
- Validate that percentages sum to 100% per configuration
- Support granular configurations (down to subject level)
- Mass-update mark settings for entire school
- Prevent invalid configurations with comprehensive validation

### Mark Types (marktypeID)
| Type | Name | Scope | Example |
|------|------|-------|---------|
| 0 | Global | All exams use same percentages | Midterm 40%, Final 60% for ALL |
| 1 | Class-wise | Per class, all exams | Class 1: 50/50, Class 2: 40/60 |
| 2 | Exam-wise (All) | Per exam, all classes | All use selected exam percentages |
| 3 | Exam-wise Individual | Per exam, separate configs | Midterm: 100%, Final: 100% |
| 4 | Subject-wise | Per class-subject combo | Math: 30/30/40, English: 50/50 |
| 5 | Class-Exam-wise | Per class-exam combo | Class 1 Midterm: 40/60 |
| 6 | Class-Exam-Subject | Most granular | Class 1, Midterm, Math: 30/30/40 |

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `/marksetting/index` | Configure and view mark settings | Standard |
| `required_markpercentages()` | Callback | Validate percentages selected | Validation |
| `required_exams()` | Callback | Validate exams selected for types needing them | Validation |
| `required_marktype()` | Callback | Ensure mark type selected | Validation |
| `check_markpercentage()` | Callback | Complex validation ensuring percentages = 100% | Validation |

## Data Layer
### Models Used
- `exam_m`: Load exam definitions
- `classes_m`: Load class configurations
- `subject_m`: Load subject definitions
- `setting_m`: Update school-wide marktypeID
- `marksetting_m`: CRUD for mark settings
- `markpercentage_m`: Load percentage components
- `marksettingrelation_m`: Junction table linking settings to percentages

### Database Tables
- `marksetting`: Defines mark configuration
  - `marksettingID` (PK)
  - `examID` (FK, 0 if not exam-specific)
  - `classesID` (FK, 0 if not class-specific)
  - `subjectID` (FK, 0 if not subject-specific)
  - `marktypeID` (0-6, indicates configuration type)
  - `schoolID` (FK)
- `marksettingrelation`: Links settings to percentages
  - `marksettingrelationID` (PK)
  - `marksettingID` (FK to marksetting)
  - `markpercentageID` (FK to markpercentage)
  - `marktypeID` (denormalized for filtering)
  - `schoolID` (FK)
- `setting`: Stores active `marktypeID` for school
- `markpercentage`: Defines components (Midterm 40%, etc.)

## Validation Rules
### Form Rules
- **marktypeID**: required, must not be empty string
- **markpercentages[]**: required, at least one percentage must be selected
- **exams[]**: required for types 0, 1, 2, 3, 5, 6 (exam-dependent types)

### Percentage Sum Validation (check_markpercentage)
For each marktypeID, validates that selected percentages sum to exactly 100%:

- **Type 0 (Global)**: Total of all percentages must = 100%
- **Type 1 (Class-wise)**: Each class's percentages must = 100%
- **Type 2 (Exam-wise All)**: Total percentages must = 100%
- **Type 3 (Exam-wise Individual)**: Each exam's percentages must = 100%
- **Type 4 (Subject-wise)**: Each class-subject combo must = 100%
- **Type 5 (Class-Exam-wise)**: Each class-exam combo must = 100%
- **Type 6 (Class-Exam-Subject)**: Each class-exam-subject combo must = 100%

Shows detailed error messages identifying which configurations are invalid.

## Dependencies & Interconnections
### Depends On (Upstream)
- **Markpercentage**: Provides percentage components to assign
- **Exam**: Exams to configure (for exam-dependent types)
- **Classes**: Classes to configure
- **Subject**: Subjects to configure (for subject-dependent types)
- **School**: All settings scoped to school

### Used By (Downstream)  
- **Mark**: Uses marksettings to determine which percentages to show for mark entry
- **Examcompilation**: Calculates totals based on configured percentages
- **Reports**: All mark-based reports rely on these settings

### Related Features
- **Grade**: Grade calculation may depend on weighted marks
- **Markrelation**: Links entered marks to configured percentages

## User Flows
### Primary Flow: Configure Global Marking (Type 0)
1. Navigate to `/marksetting/index`
2. Select marktypeID: "Global"
3. Select exams (e.g., "Midterm", "Final")
4. Select mark percentages: 
   - Midterm Exam: 40%
   - Final Exam: 60%
5. System validates: 40 + 60 = 100% ✓
6. Save configuration
7. System:
   - Updates `setting` table with marktypeID = 0
   - Deletes old marksetting records for type 0
   - Creates new marksetting records (one per exam)
   - Creates marksettingrelation records linking to percentages
8. Mark entry now shows these percentages for all exams

### Primary Flow: Configure Subject-wise (Type 4)
1. Select marktypeID: "Subject-wise" (4)
2. For each class-subject combination:
   - Math in Class 1: Quiz 10%, Homework 10%, Midterm 30%, Project 20%, Final 30%
   - English in Class 1: Midterm 50%, Final 50%
3. System validates each combo sums to 100%
4. Save creates marksetting + marksettingrelation for each combo
5. Teachers see different percentage breakdowns per subject

### Primary Flow: Validation Failure
1. Configure Class-Exam-wise (Type 5)
2. Class 1, Midterm: Select Midterm 40%, Project 50% (total 90%)
3. Submit form
4. System calculates: 40 + 50 = 90 ≠ 100
5. Display error: "Select mark percentage in 100 percent of exam Midterm in class Class 1"
6. User adjusts percentages
7. Resubmit until valid

## Edge Cases & Limitations
- **Graduated Class Exclusion**: `ex_class` excluded from class selections
- **Destructive Updates**: Saving deletes ALL previous settings for that marktypeID
- **No Partial Configs**: All required configurations must be complete (e.g., all classes)
- **Exam Selection**: Some types require exams, others don't (validated per type)
- **Percentage Uniqueness**: Uses combo of type+percentage for identifying selections
- **Complex Validation**: check_markpercentage() has 250+ lines of validation logic
- **Type 6 Complexity**: Most granular type requires selection for every class-exam-subject combo
- **No Mixing Types**: Cannot use multiple marktypeID simultaneously (one per school)

## Configuration
- **Active Type Storage**: `setting.marktypeID` stores currently active marking scheme
- **Session Variables**:
  - `schoolID`: Current school context
- **System Settings**:
  - `ex_class`: Graduated class ID to exclude from configurations

## Notes for AI Agents
- **Longest Controller Logic**: check_markpercentage() callback is 300+ lines
- **Batch Processing**: Uses batch insert/delete for efficiency
- **Destructive Pattern**: Always deletes before inserting (no UPDATE operations)
- **Type-Specific Logic**: Each marktypeID has completely different configuration logic
- **UI Complexity**: Frontend must dynamically show different forms based on marktypeID
- **Validation Heavy**: Most complex validation in entire system
- **Error Messages**: Builds concatenated error strings for multiple violations
- **Performance**: Uses pluck() pattern extensively for efficient lookups
- **Data Denormalization**: marktypeID stored in both marksetting and marksettingrelation
- **Atomicity**: Should be wrapped in transaction (currently not)
- **ID Generation**: Uses insert_batch_marksetting() which returns first inserted ID
- **Incremental IDs**: Assumes auto-increment IDs are sequential for batch relation creation
- **Frontend Dependency**: Requires complex JavaScript for dynamic form rendering
- **Testing Complexity**: Each of 7 types requires separate test scenarios
- **Migration Risk**: Changing marktypeID wipes existing config - no rollback
- **Recommendation**: Add transaction support and backup before changing marktypeID
- **Type Selection Guide**:
  - Simple school: Type 0 (Global)
  - Different classes, same exam structure: Type 1 (Class-wise)
  - Different exams, same across classes: Type 3 (Exam-wise Individual)
  - Different subjects need different breakdowns: Type 4 (Subject-wise)
  - Maximum flexibility: Type 6 (Class-Exam-Subject) - but most complex

