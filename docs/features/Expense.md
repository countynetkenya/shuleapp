# Feature: Expense

## Overview
**Controller**: `mvc/controllers/Expense.php`  
**Primary Purpose**: Track school operating expenses with file attachments and audit trails  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete expense records
- File attachment support (PDF, DOC, images, spreadsheets)
- Date validation within school year
- User and usertype audit tracking (who created/modified)
- School year scoped expenses
- Download attached expense documents

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | expense | List expenses for current school year | expense |
| `add()` | expense/add | Create new expense | expense_add |
| `edit(id)` | expense/edit/[id] | Edit existing expense | expense_edit |
| `delete(id)` | expense/delete/[id] | Delete expense | expense_delete |
| `download(id)` | expense/download/[id] | Download attached file | expense |
| `fileupload()` | N/A | Validation callback for file upload | N/A |
| `date_valid()` | N/A | Validation callback for date format | N/A |
| `valid_number()` | N/A | Validation callback for positive amount | N/A |
| `unique_date()` | N/A | Validation callback for date within school year | N/A |

## Data Layer
### Models Used
- expense_m

### Database Tables
- `expense` - Expense records
  - expenseID (PK), expense (name/description)
  - amount, date, expenseday, expensemonth, expenseyear
  - file (attachment filename), note
  - create_date, usertypeID, uname, userID
  - schoolyearID, schoolID

## Validation Rules
### Add/Edit Expense
- `expense`: required, max 128 chars, xss_clean
- `date`: required, dd-mm-yyyy format, 10 chars max, xss_clean, callback validates format + school year range
- `amount`: required, numeric, max 11 chars, xss_clean, callback validates >= 0
- `file`: optional, max 200 chars, xss_clean, callback handles upload
- `note`: optional, max 200 chars, xss_clean

### File Upload
- Allowed types: gif, jpg, png, jpeg, pdf, doc, xml, docx, xls, xlsx, txt, ppt, csv
- Max size: 5120 KB (5 MB)
- Max dimensions: 3000x3000 px
- Filename hashed with SHA-512 for security
- Stored in: `uploads/images/`

### Date Validation
- Format: dd-mm-yyyy (e.g., "25-12-2024")
- Must fall within school year startingdate and endingdate
- Uses `checkdate()` for calendar validation

## Dependencies & Interconnections
### Depends On (Upstream)
- **Schoolyear**: Expenses scoped to school year

### Used By (Downstream)
- Financial reports (expense tracking)

### Related Features
- Income, Purchase, Schoolyear

## User Flows
### Primary Flow: Create Expense
1. Admin navigates to expense/add (only if active school year or superadmin)
2. Enter expense name, amount, date, note
3. Optionally attach file (receipt, invoice)
4. Submit creates expense record with:
   - Date broken into day, month, year columns
   - File hashed and uploaded
   - Audit fields: userID, usertypeID, uname
   - create_date = today
5. Redirects to index with success message

### Edit Expense Flow
1. Load existing expense (scoped to school year + schoolID)
2. Modify fields (file optional, keeps existing if not changed)
3. Submit updates expense (no create_date change)
4. Updates audit fields: userID, usertypeID, uname

### Download Attachment Flow
1. User clicks download link with expenseID
2. Backend retrieves expense, validates permissions
3. Constructs original filename from expense name + file extension
4. Sends file with Content-Disposition: attachment header
5. Browser downloads file

## Edge Cases & Limitations
- **School Year Lock**: Can only add/edit in active year (unless superadmin or systemadmin)
- **File Overwrite**: Edit replaces old file, doesn't delete previous (orphaned files)
- **Date Storage**: Redundant storage (date, expenseday, expensemonth, expenseyear)
- **Negative Amounts**: Validation prevents but database allows
- **Filename Hash**: Original filename lost (reconstructed from expense name + extension)
- **No File Delete**: Deleting expense doesn't delete attached file from disk
- **School Year Boundary**: Date must be within school year dates (strict validation)

## Configuration
- No environment variables
- File upload path: `./uploads/images` (hardcoded)

## Notes for AI Agents
### File Upload Security
```php
$random = random19(); // 19-char random string
$makeRandom = hash('sha512', $random . $name . config_item("encryption_key"));
$filename = $makeRandom . '.' . $extension;
```
- Prevents filename collisions
- Obscures original filename for security
- Uses encryption key as salt

### Date Parsing Logic
```php
// Input: "25-12-2024" (dd-mm-yyyy)
$arr = explode("-", $date);
$dd = $arr[0];
$mm = $arr[1];
$yyyy = $arr[2];

// Storage:
$date = date("Y-m-d", strtotime($date)); // 2024-12-25
$expenseday = date("d", strtotime($date)); // 25
$expensemonth = date("m", strtotime($date)); // 12
$expenseyear = date("Y", strtotime($date)); // 2024
```

### School Year Validation
```php
$date = strtotime($this->input->post('date'));
$startdate = strtotime($this->data['schoolyearsessionobj']->startingdate);
$endingdate = strtotime($this->data['schoolyearsessionobj']->endingdate);
if(($date < $startdate) || ($date > $endingdate)) {
    // Validation fails
}
```

### Audit Trail Pattern
- Captures: usertypeID, userID, uname (username)
- Create: stores create_date
- Edit: updates userID fields (no modify_date)
- Useful for accountability

### Download Headers
```php
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$originalname.'"');
header('Cache-Control: must-revalidate');
header('Content-Length: ' . filesize($file));
readfile($file);
```

### getAllUserObjectWithStudentRelation()
- Helper function to get all users with student relations
- Used for displaying "who created" in index view
- Scoped to schoolyearID and schoolID

### Performance Considerations
- File upload blocking (synchronous)
- No file size streaming (loads entire file into memory)
- Consider chunked upload for large files

