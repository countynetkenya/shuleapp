# Feature: Hmember (Hostel Member)

## Overview
**Controller**: `mvc/controllers/Hmember.php`  
**Primary Purpose**: Manages student hostel membership assignments with room category and fee tracking.  
**User Roles**: Admins, Hostel Managers, Superadmin (Students can view own)  
**Status**: ✅ Active

## Functionality

### Core Features
- Assign students to hostels with room categories
- Track hostel fees (hbalance) per category
- View students by class with hostel status
- Prevent duplicate hostel assignments
- View student hostel details
- Auto-update student.hostel flag (0=no hostel, 1=in hostel)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `hmember/index/{classID}` | List students by class with hostel status | `hmember_view` or own class (student) |
| `add()` | `hmember/add/{studentID}/{classID}` | Assign student to hostel | `hmember` |
| `edit()` | `hmember/edit/{studentID}/{classID}` | Edit hostel assignment | `hmember` |
| `delete()` | `hmember/delete/{studentID}/{classID}` | Remove from hostel | `hmember` |
| `view()` | `hmember/view/{studentID}/{classID}` | View student hostel details | `hmember_view` or self (student) |

## Data Layer

### Models Used
- `hmember_m`: Hostel membership CRUD
- `student_m`: Student data and hostel flag
- `studentrelation_m`: Student-class relationships
- `hostel_m`: Hostel information
- `category_m`: Room categories and fees
- `classes_m`, `section_m`: Class/section data

### Database Tables
- `hmember`: `hmemberID`, `hostelID`, `categoryID`, `studentID`, `hbalance` (fee), `hjoindate`, `schoolID`
- `student`: Has `hostel` flag (0/1)
- Related: `hostel`, `category`, `studentrelation`

## Validation Rules
- **hostelID**: Required, numeric, max 11, gender validation callback
- **categoryID**: Required, numeric, max 11, not '0', unique category callback

### Custom Validations
- **unique_gender**: Checks hostel gender matches student gender
- **unique_select**: Ensures dropdown not '0'
- **unique_category**: Validates category belongs to selected hostel

## Dependencies & Interconnections

### Depends On (Upstream)
- **Student**: Requires student records
- **Hostel**: Hostel definitions
- **Category**: Room categories with fees
- **Studentrelation**: Class enrollment

### Used By (Downstream)
- **Hostelreport**: Hostel occupancy reports
- **Invoice**: May generate hostel fee invoices

### Related Features
- **Hostel**: Hostel management
- **Category**: Room category configuration
- **Tmember**: Transport membership (parallel structure)

## User Flows

### Primary Flow: Assign Student to Hostel
1. Admin navigates to Hmember, selects class
2. System lists students, filters those not in hostel (hostel=0)
3. Admin clicks "Add" on non-hostel student
4. Selects hostel from dropdown
5. System loads room categories for that hostel
6. Selects category (e.g., "Single Room", "Double Room")
7. System auto-fills hbalance from category.hbalance
8. Sets hjoindate to current date
9. Creates hmember record
10. Updates student.hostel = 1
11. Redirects to class index

### Edit Flow
1. Click edit on student with hostel
2. Change hostel or category
3. System updates hbalance to new category fee
4. Maintains hjoindate (join date preserved)
5. Updates hmember record
6. Redirects to class index

### Delete Flow
1. Click delete on hostel student
2. System removes hmember record
3. Sets student.hostel = 0
4. Redirects to class index

## Edge Cases & Limitations
- **One Hostel Per Student**: Can't assign to multiple hostels
- **Class-Based Navigation**: Requires classID in URL for context
- **Gender Validation**: Callback checks hostel.hgender vs student.sex (1=Boys, 2=Girls, 3=Mixed)
- **Balance Copy**: hbalance copied from category on assign (doesn't auto-update if category fee changes)
- **Join Date**: Set on create, preserved on edit (no leave/rejoin tracking)
- **Student View**: Students (type 3) can view own hostel info if permitted

## Configuration
- Scoped by `schoolID` and `schoolyearID`
- Year lock for add/edit

## Notes for AI Agents

### Implementation Details
- **Hostel Flag**: Updates student table hostel field (0/1) to track status
- **Balance Denormalization**: Copies category.hbalance to hmember.hbalance (not FK)
- **Gender Callback**: `unique_gender()` validates hostel.hgender compatibility
- **Class Context**: Most routes require both studentID and classID parameters

### Business Logic
- **Permission**: `hmember` for assign/edit/delete, `hmember_view` for viewing
- **Student Self-Access**: Type 3 users redirected to own view if lacking view permission
- **School Year Lock**: Can only add/edit for current year (except superadmin)

### Performance
- Lists all students in class (no pagination)
- Multiple queries per student on index (hmember + category lookups)

### Common Pitfalls
- **Deleting Hostel/Category**: No cascade check before deletion
- **Balance Stale**: If category fee changes, existing hmember records unchanged
- **Gender Mismatch**: Error if assigning student to wrong-gender hostel
