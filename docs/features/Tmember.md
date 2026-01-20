# Feature: Tmember (Transport Member)

## Overview
**Controller**: `mvc/controllers/Tmember.php`  
**Primary Purpose**: Manages student transport membership with route assignment and fee tracking.  
**User Roles**: Admins, Transport Managers, Superadmin (Students can view own)  
**Status**: ✅ Active

## Functionality

### Core Features
- Assign students to transport routes
- Track transport fees (tbalance) per route
- View students by class with transport status
- Prevent duplicate transport assignments
- View student transport details with PDF/email support
- Auto-update student.transport flag (0/1)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `tmember/index/{classID}` | List students by class with transport status | `tmember_view` or own class (student) |
| `add()` | `tmember/add/{studentID}/{classID}` | Assign student to transport | `tmember` |
| `edit()` | `tmember/edit/{studentID}/{classID}` | Edit transport assignment | `tmember` |
| `delete()` | `tmember/delete/{studentID}/{classID}` | Remove from transport | `tmember` |
| `view()` | `tmember/view/{studentID}/{classID}` | View student transport details | `tmember_view` or self |
| `print_preview()` | `tmember/print_preview/{studentID}/{classID}` | Generate PDF | `tmember_view` or self |
| `send_mail()` | AJAX POST | Email transport details | `tmember_view` or self |
| `student_list()` | AJAX POST | Navigate to class | - |
| `transport_fare()` | AJAX POST | Get route fare | - |

## Data Layer

### Models Used
- `tmember_m`: Transport membership CRUD
- `student_m`: Student data and transport flag
- `studentrelation_m`: Student-class relationships
- `transport_m`: Transport route information
- `classes_m`, `section_m`: Class/section data

### Database Tables
- `tmember`: `tmemberID`, `studentID`, `transportID`, `name`, `email`, `phone`, `tbalance` (fee), `tjoindate`, `schoolID`
- `student`: Has `transport` flag (0/1)
- Related: `transport`, `studentrelation`

## Validation Rules
- **transportID**: Required, max 11, not '0'
- **tbalance**: Required, numeric, max 20, must be ≥ 0

### Custom Validations
- **unique_transportID**: Ensures transportID not '0'
- **valid_number**: Ensures tbalance ≥ 0 (no negative fees)

## Dependencies & Interconnections

### Depends On (Upstream)
- **Student**: Requires student records
- **Transport**: Route definitions with fares
- **Studentrelation**: Class enrollment

### Used By (Downstream)
- **Transportreport**: Transport usage reports
- **Invoice**: May generate transport fee invoices

### Related Features
- **Transport**: Route management
- **Hmember**: Hostel membership (parallel structure)

## User Flows

### Primary Flow: Assign Student to Transport
1. Admin navigates to Tmember, selects class
2. System lists students, filters those without transport (transport=0)
3. Admin clicks "Add" on non-transport student
4. Selects transport route from dropdown
5. System auto-fills tbalance from transport.fare (via AJAX `transport_fare()`)
6. Can manually adjust tbalance if needed
7. Copies student name, email, phone to tmember
8. Sets tjoindate to current date
9. Creates tmember record
10. Updates student.transport = 1
11. Redirects to class index

### View/Print Flow
1. Click view on transport student
2. System displays student info, transport route, and fee details
3. Options to print PDF or email
4. PDF/email includes student and transport information

## Edge Cases & Limitations
- **One Route Per Student**: Can't assign to multiple routes
- **Data Denormalization**: Copies name/email/phone to tmember (can become stale)
- **Balance Manual Override**: tbalance can differ from transport.fare (custom discounts)
- **Join Date**: Set on create, preserved on edit
- **No Leave/Rejoin Tracking**: Simple active/inactive via transport flag
- **Student Self-Access**: Students (type 3) can view/print own transport info

## Configuration
- Scoped by `schoolID` and `schoolyearID`
- Year lock for add/edit
- Uses select2 for dropdowns

## Notes for AI Agents

### Implementation Details
- **Transport Flag**: Updates student.transport (0/1) to track status
- **Data Copy**: Stores snapshot of student.name/email/phone in tmember
- **AJAX Fare Lookup**: `transport_fare()` returns transport.fare for auto-fill
- **PDF/Email**: Supports generating and emailing transport details
- **Permission Model**: `tmember` for manage, `tmember_view` for viewing

### Business Logic
- **Balance Flexibility**: tbalance can be manually set (not enforced to equal transport.fare)
- **Student Access**: Type 3 users redirected to own view if lacking view permission
- **School Year Lock**: Add/edit restricted to current year (except superadmin)

### Performance
- Lists all students in class (no pagination)
- Multiple queries per student on index

### Common Pitfalls
- **Deleting Transport**: No cascade check before route deletion
- **Stale Data**: Student name/email changes don't auto-update tmember
- **Balance Sync**: Changing transport.fare doesn't update existing tmember.tbalance
