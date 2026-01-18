# Feature: Online_exam

## Overview
**Controller**: `mvc/controllers/Online_exam.php`  
**Primary Purpose**: Create and manage online/computer-based exams with questions from question bank. Supports multiple exam types (anytime, date-range, date-time-range), random question ordering, and auto-grading.  
**User Roles**: Admin, Teachers (create/manage), Students (view published exams)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create online exams with configurable parameters
- Associate questions from question bank
- Support 3 exam timing types:
  - Type 2: Anytime exam (always available)
  - Type 4: Date-range exam (available between two dates)
  - Type 5: DateTime-range exam (precise start/end times)
- Configure exam duration, passing criteria, negative marking
- Random question ordering option
- Filter questions by level and group
- Publish/unpublish exams
- Student-specific exam access control (class, section, group, subject)
- Free or paid exam support

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `/online_exam/index` | List all online exams | Standard |
| `add()` | `/online_exam/add` | Create new online exam | School year check |
| `edit()` | `/online_exam/edit/{onlineExamID}` | Update exam configuration | School year check |
| `delete()` | `/online_exam/delete/{onlineExamID}` | Remove online exam | School year check |
| `addquestion()` | `/online_exam/addquestion/{onlineExamID}` | Associate questions with exam | Permission check |
| `showQuestions()` | AJAX POST | Load filtered question list | AJAX |
| `addQuestionDatabase()` | AJAX POST | Add question to exam | AJAX |
| `removeQuestionDatabase()` | AJAX POST | Remove question from exam | AJAX |
| `getSection()` | AJAX POST | Load sections for selected class | AJAX |
| `getSubject()` | AJAX POST | Load subjects for selected class | AJAX |

## Data Layer
### Models Used
- `online_exam_m`: CRUD for online exams
- `classes_m`, `section_m`, `subject_m`: Student filtering
- `studentgroup_m`: Group-based filtering
- `usertype_m`: Permission handling
- `exam_type_m`: Exam timing types
- `question_bank_m`: Question repository
- `question_level_m`, `question_group_m`, `question_type_m`: Question organization
- `question_option_m`, `question_answer_m`: Question details
- `online_exam_question_m`: Junction table exam↔question
- `student_m`, `instruction_m`: Additional data
- `studentrelation_m`: Student enrollment data

### Database Tables
- `online_exam`: Main exam configuration
  - `onlineExamID` (PK)
  - `name` (exam title, max 128 chars)
  - `description` (HTML text)
  - `classID`, `sectionID`, `studentGroupID`, `subjectID` (FK filters)
  - `instructionID` (FK to instruction)
  - `examTypeNumber` (2/4/5: timing type)
  - `startDateTime`, `endDateTime` (nullable, for types 4 & 5)
  - `duration` (minutes, nullable)
  - `random` (1/0: randomize question order)
  - `markType` (5=percentage, 10=numeric)
  - `percentage` (passing threshold)
  - `negativeMark` (numeric penalty for wrong answers)
  - `examStatus` (1=one-time, 2=multiple attempts)
  - `published` (1=visible, 0=hidden)
  - `paid`, `cost`, `validDays`, `judge` (payment/validity)
  - `create_date`, `modify_date`, `create_userID`, `create_usertypeID`
  - `schoolYearID`, `schoolID`
- `online_exam_question`: Links exams to questions
  - `onlineExamQuestionID` (PK)
  - `onlineExamID` (FK to online_exam)
  - `questionID` (FK to question_bank)
  - `schoolID` (FK)

## Validation Rules
### Exam Configuration
- **name**: required, max 128 chars
- **description**: optional, HTML allowed
- **classes**: required, numeric
- **section**: required, numeric, must match class (callback: unique_section)
- **studentGroup**: required, numeric
- **subject**: numeric (optional)
- **instruction**: required, numeric
- **examStatus**: required, numeric, not 0 (callback: unique_data)
- **type**: required, numeric, not 0 (callback: unique_type)
- **duration**: numeric (minutes)
- **markType**: required, numeric, not 0 (callback: unique_markType)
- **negativeMark**: numeric
- **percentage**: required, numeric (passing threshold)
- **random**: required, numeric, not 0 (1=yes, 0=no)
- **published**: required, numeric, not 0, must have questions if =1 (callback: check_exam_question)

### Type-Specific Rules
- **Type 4** (Date-range):
  - startdate: required, valid date
  - enddate: required, valid date, must be >= startdate
- **Type 5** (DateTime-range):
  - startdatetime: required, valid datetime
  - enddatetime: required, valid datetime, must be >= startdatetime

## Dependencies & Interconnections
### Depends On (Upstream)
- **Question_bank**: Source of exam questions
- **Question_group**: Organize questions by topic
- **Question_level**: Filter by difficulty
- **Classes**, **Section**, **Subject**: Student filtering
- **Studentgroup**: Additional student filtering
- **Exam_type**: Defines timing behavior
- **Instruction**: Provides exam rules to students
- **Schoolyear**: Exams scoped to academic year

### Used By (Downstream)  
- **Take_exam**: Students take published exams
- **Online_exam_user_status**: Tracks exam attempts and scores
- **Online_exam_user_answer**: Stores student responses
- **Onlineexamreport**: Generates exam result reports

### Related Features
- **Mark**: Online exam scores may integrate with traditional marks
- **Student**: Students take exams based on class/section/subject enrollment
- **Certificate**: May generate certificates based on exam performance

## User Flows
### Primary Flow: Create Online Exam
1. Navigate to `/online_exam/add`
2. Enter exam name and description
3. Select target: class, section, student group, optional subject
4. Select exam type (Anytime/Date-range/DateTime-range)
5. If type 4/5: Set start and end dates/times
6. Configure:
   - Duration (minutes)
   - Mark type (percentage/numeric)
   - Passing threshold
   - Negative marking
   - Exam status (one-time/multiple)
   - Random question order (yes/no)
7. Select instruction template
8. Save as unpublished (published=0)
9. Navigate to add questions
10. Filter questions by level/group
11. Select questions to add
12. Verify question summary
13. Edit exam, set published=1
14. Students can now see and take exam

### Primary Flow: Add Questions to Exam
1. Open exam in edit mode
2. Click "Add Questions" tab
3. Filter by question level (Easy/Medium/Hard) and/or group (topic)
4. System displays matching questions from bank
5. Click checkbox to select question
6. AJAX call creates `online_exam_question` record
7. Question appears in "Associated Questions" panel
8. Repeat to add more questions
9. Remove questions with "X" button if needed
10. View question summary (total marks, count)

### Primary Flow: Student Takes Exam
1. Student logs in, navigates to exam list
2. System filters exams by student's class/section/group/subject
3. Displays available published exams
4. Student clicks exam
5. System checks:
   - Exam timing (type 4/5: within date/time range?)
   - Prior attempts (examStatus=1: already taken?)
   - Subject enrollment (optional subject match?)
6. If all pass: Display instruction page
7. Student starts exam (see Take_exam feature)

## Edge Cases & Limitations
- **Question Requirement**: Cannot publish exam without questions (validated)
- **Date Validation**: Start must be <= End for types 4 and 5
- **Subject Filtering**: If exam has subjectID, only students enrolled in that subject can access
- **Multiple Attempts**: examStatus=2 allows retakes, =1 blocks after first attempt
- **School Year Lock**: Can only create/edit in current school year (unless superadmin)
- **Random Order**: If random=1, questions shown in random order each attempt
- **Timing Types**:
  - Type 2: No timing restrictions
  - Type 4: Compares dates only (ignores time)
  - Type 5: Exact datetime comparison
- **Negative Marking**: If negativeMark > 0, wrong answers deduct points
- **Mark Type**:
  - Type 5: Passing based on percentage (e.g., 60%)
  - Type 10: Passing based on raw marks (e.g., 50 points)

## Configuration
- **Session Variables**:
  - `schoolID`: Current school
  - `defaultschoolyearID`: Active academic year
  - `usertypeID`: User role
  - `loginuserID`: Current user
- **System Settings**:
  - `school_year` in siteinfos: For school year lock validation

## Notes for AI Agents
- **Complex Timing Logic**: 3 different exam type behaviors (always/date-range/datetime-range)
- **AJAX-Heavy UI**: Question selection uses extensive AJAX for dynamic loading
- **Validation Callback**: check_exam_question() ensures published exams have questions
- **Random Implementation**: Uses SQL `ORDER BY RANDOM` when retrieving questions for students
- **Student Filtering**: Multiple levels (class→section→group→subject)
- **Published Flag**: Key control - students only see published=1 exams
- **Question Association**: Uses junction table pattern (online_exam_question)
- **Reusability**: Same question can be in multiple exams
- **Auto-Grading**: System auto-grades MCQ and fill-in-blanks (see Take_exam)
- **Payment Support**: Has `paid`, `cost`, `validDays` fields (payment integration)
- **Judge Field**: Purpose unclear, possibly for manual grading override
- **DateTime Storage**: Uses 'Y-m-d H:i:s' format for datetime fields
- **Frontend Assets**: Uses datetimepicker, select2, checkbox CSS
- **Performance**: Loads all questions in addquestion() - could be paginated for large banks
- **Missing Validation**: Should validate that questions belong to same schoolID
- **Security**: Uses escapeString() and htmlentities() for URI segments
- **Denormalization**: None in this table (normalized design)
- **Integration Point**: Results stored separately in online_exam_user_* tables

