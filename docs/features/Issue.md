# Feature: Issue

## Overview
**Controller**: `mvc/controllers/Issue.php`  
**Primary Purpose**: Manages library book issuance and return tracking, including fine generation for overdue books  
**User Roles**: Admin, Librarian, Student (view own), Parent (view children's)  
**Status**: ✅ Active

## Functionality
### Core Features
- Book issue/checkout to library members
- Book return tracking with return date
- Due date management and overdue tracking
- Fine/penalty invoice generation for overdue books
- Serial number tracking for book copies
- Multi-role access (Admin views all, Students/Parents view own)
- Email and PDF report generation for issues
- Parent-specific student selection interface

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `issue/index/{lID?}` | List issues (filtered by role/lID) | `issue` |
| `add()` | `issue/add` | Issue new book to member | `issue_add` |
| `edit()` | `issue/edit/{id}/{lID}` | Edit issue details | `issue_edit` |
| `view()` | `issue/view/{id}` | View single issue details | `issue_view` |
| `returnbook()` | `issue/returnbook/{id}/{lID}` | Process book return | `issue_add` + `issue_edit` |
| `print_preview()` | `issue/print_preview/{id}` | Generate PDF of issue | `issue_view` |
| `send_mail()` | `issue/send_mail` | Email issue details | `issue_view` |
| `add_invoice()` | `issue/add_invoice` | Create fine invoice | `issue_add` + `issue_edit` |
| `bookIDcall()` | `issue/bookIDcall` | AJAX: Get book details | - |
| `student_list()` | `issue/student_list` | AJAX: Redirect to student issues | - |

## Data Layer
### Models Used
- `lmember_m` - Library member operations
- `book_m` - Book catalog queries
- `issue_m` - Issue record management
- `student_m` - Student data
- `studentrelation_m` - Student-school year relations
- `classes_m` - Class information
- `section_m` - Section information
- `parents_m` - Parent data
- `invoice_m` - Fine invoice creation
- `feetypes_m` - Fee type management
- `maininvoice_m` - Main invoice records

### Database Tables
- `issue` - Book issue records:
  - `issueID` (PK)
  - `lID` - Library member ID
  - `bookID` - Book reference
  - `serial_no` - Book copy serial number
  - `issue_date` - Issue date (set to today on creation)
  - `due_date` - Return due date
  - `return_date` - Actual return date (NULL if not returned)
  - `note` - Additional notes
  - `schoolID` - School identifier
- `lmember` - Library members
- `book` - Book catalog (due_quantity updated)
- `invoice` - Fine invoices for overdue books
- `maininvoice` - Parent invoice records

## Validation Rules
1. **lid**: Required, max 40 chars, XSS clean, must exist in lmember table
2. **book**: Required, max 60 chars, XSS clean, must exist, must have available copies, not already issued to same member
3. **author**: Required, XSS clean (auto-populated)
4. **subject_code**: Required, XSS clean (auto-populated)
5. **serial_no**: Required, max 40 chars, XSS clean
6. **due_date**: Required, max 10 chars, valid date format (dd-mm-yyyy), must be >= today
7. **note**: Optional, max 200 chars, XSS clean

## Dependencies & Interconnections
### Depends On (Upstream)
- `Book` - Book catalog and availability
- `Lmember` - Library membership validation
- `Student` / `Studentrelation` - Student information
- `Feetypes` - Fee type for book fines

### Used By (Downstream)
- `Invoice` - Fine invoices generated for overdue books
- `Maininvoice` - Parent invoice records

### Related Features
- **Book**: Source of issued books, `due_quantity` updated
- **Lmember**: Library members who borrow books
- **Invoice**: Fines for late returns

## User Flows
### Primary Flow: Issue Book to Member
1. Admin/Librarian navigates to `issue/add`
2. Enters library member ID (lID)
3. Selects book from dropdown (auto-populates author, subject_code)
4. System validates book availability (quantity > due_quantity)
5. System checks member doesn't already have this book issued
6. Enters serial number and due date (must be >= today)
7. System sets issue_date to today
8. System increments book's `due_quantity` by 1
9. Issue record created, redirect to index

### Secondary Flow: Return Book
1. Admin clicks "Return" on active issue
2. System validates issue exists and book is not already returned
3. System sets return_date to today
4. System decrements book's `due_quantity` by 1
5. Success message, redirect to issue index

### Fine Generation Flow
1. Admin views issue index with overdue books
2. Admin clicks "Add Fine" for overdue issue
3. Enters fine amount
4. System creates/gets "Book Fine" fee type
5. System creates maininvoice record
6. System creates invoice linked to maininvoice
7. Invoice appears in student's fee records

### Role-Based Index Flow
- **Student (usertypeID=3)**: Shows only own issues (via studentID → lID)
- **Parent (usertypeID=4)**: Shows student selector, then selected student's issues
- **Admin/Others**: Shows search interface, can view any member's issues by lID

## Edge Cases & Limitations
1. **Book Availability**: Validates `due_quantity < quantity` before allowing issue
2. **Duplicate Issues**: Prevents same book being issued to same member if already active (return_date = NULL)
3. **Date Validation**: Due date must be >= current date, validates dd-mm-yyyy format
4. **School Year Restriction**: Add/Edit/Return only allowed if current school year or superadmin
5. **Return Validation**: Only creator or those with both add+edit permissions can return books
6. **Self-Approval**: User cannot approve their own stock entries
7. **Parent View**: Has disabled library check (`&& 0 == 1` in code) - parent view may not work fully
8. **Email Sending**: Uses `reportSendToMail()` method from parent controller

## Configuration
- Language file: `mvc/language/{lang}/issue_lang.php`
- PDF template: `views/issue/print_preview.php`
- CSS for PDF: `issuemodule.css`
- Default fee type name: "Book Fine" (from language file)

## Notes for AI Agents
- **Book Quantity Sync**: Critical to maintain `book.due_quantity` - incremented on issue, decremented on return
- **Return Date Logic**: NULL = active issue, non-NULL = returned book
- **Fine System**: Not automatic - admin must manually create fine invoice with amount
- **Parent View Bug**: Line 114 has `&& 0 == 1` which disables library member display for parents
- **Serial Number**: Tracks individual book copies, not validated against book inventory
- **School Year Lock**: Most operations restricted to current school year except for superadmin
- **Multi-Step Search**: Index requires searching by lID first (not student name), could be improved
- **Invoice Creation**: Auto-creates "Book Fine" fee type if doesn't exist, links to student's maininvoice
- **AJAX Methods**: `bookIDcall()` provides book details, `student_list()` handles parent's student selection
