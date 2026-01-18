# Feature: Income

## Overview
**Controller**: `mvc/controllers/Income.php`  
**Primary Purpose**: Track school non-tuition income (donations, grants, fundraising, misc revenue) with file attachments  
**User Roles**: Admin (1), Systemadmin (5)  
**Status**: ✅ Active

## Functionality
### Core Features
- Create/edit/delete income records
- File attachment support (receipts, documentation)
- Date validation within school year
- User audit tracking (userID, usertypeID)
- School year scoped income
- Download attached income documents
- File deletion on record delete (if not demo mode)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | income | List income for current school year | income |
| `add()` | income/add | Create new income record | income_add |
| `edit(id)` | income/edit/[id] | Edit existing income | income_edit |
| `delete(id)` | income/delete/[id] | Delete income and file | income_delete |
| `download(id)` | income/download/[id] | Download attached file | income |
| `fileupload()` | N/A | Validation callback for file upload | N/A |
| `date_valid()` | N/A | Validation callback for date format | N/A |
| `valid_number()` | N/A | Validation callback for positive amount | N/A |
| `unique_date()` | N/A | Validation callback for date within school year | N/A |

## Data Layer
### Models Used
- income_m

### Database Tables
- `income` - Income records
  - incomeID (PK), name (income source/description)
  - amount, date, incomeday, incomemonth, incomeyear
  - file (attachment filename), note
  - create_date, userID, usertypeID
  - schoolyearID, schoolID

## Validation Rules
### Add/Edit Income
- `name`: required, max 128 chars, xss_clean
- `date`: required, dd-mm-yyyy format, 10 chars max, xss_clean, callback validates format + school year range
- `amount`: required, numeric, max 10 chars, xss_clean, callback validates >= 0
- `file`: optional, max 200 chars, xss_clean, callback handles upload
- `note`: optional, max 128 chars, xss_clean

### File Upload
- Same as Expense: gif, jpg, png, jpeg, pdf, doc, xml, docx, xls, xlsx, txt, ppt, csv
- Max size: 5120 KB (5 MB)
- Max dimensions: 3000x3000 px
- Filename hashed with SHA-512
- Stored in: `uploads/images/`

### Date Validation
- Format: dd-mm-yyyy
- Must fall within school year startingdate and endingdate
- Uses `checkdate()` for calendar validation

## Dependencies & Interconnections
### Depends On (Upstream)
- **Schoolyear**: Income scoped to school year

### Used By (Downstream)
- Financial reports (income tracking)

### Related Features
- Expense, Purchase, Schoolyear

## User Flows
### Primary Flow: Create Income
1. Admin navigates to income/add (only if active school year or superadmin/systemadmin)
2. Enter income name, amount, date, note
3. Optionally attach file (receipt, grant letter)
4. Submit creates income record with:
   - Date broken into day, month, year columns
   - File hashed and uploaded
   - Audit fields: userID, usertypeID
   - create_date = today
5. Redirects to index with success message

### Delete Income Flow (differs from Expense)
1. User clicks delete
2. Backend retrieves income, validates permissions
3. If not demo mode, deletes attached file from disk:
   ```php
   if(file_exists(FCPATH.'uploads/images/'.$income->file)) {
       unlink(FCPATH.'uploads/images/'.$income->file);
   }
   ```
4. Deletes database record
5. Redirects with success message

## Edge Cases & Limitations
- **School Year Lock**: Can only add/edit in active year (unless superadmin or systemadmin)
- **File Cleanup**: Delete removes file from disk (unlike Expense which orphans files)
- **Demo Mode File Protection**: Files not deleted in demo mode
- **Date Storage**: Redundant storage (date, incomeday, incomemonth, incomeyear)
- **Negative Amounts**: Validation prevents but database allows
- **Filename Hash**: Original filename lost (reconstructed from income name + extension)
- **School Year Boundary**: Date must be within school year dates

## Configuration
- No environment variables
- File upload path: `./uploads/images` (hardcoded)
- Demo mode check: `config_item('demo')`

## Notes for AI Agents
### Income vs Expense Differences
1. **File Deletion**: Income deletes file on delete, Expense doesn't
2. **Field Names**: Income uses `name`, Expense uses `expense`
3. **Amount Limit**: Income max 10 chars, Expense max 11 chars
4. **Note Limit**: Income max 128 chars, Expense max 200 chars

### File Upload Security
```php
$random = random19();
$makeRandom = hash('sha512', $random . $name . config_item("encryption_key"));
$filename = $makeRandom . '.' . $extension;
```
- Same pattern as Expense
- Prevents collisions and guessing filenames

### Date Parsing Logic
```php
// Input: "25-12-2024" (dd-mm-yyyy)
$array['date'] = date("Y-m-d", strtotime($date)); // 2024-12-25
$array['incomeday'] = date("d", strtotime($date)); // 25
$array['incomemonth'] = date("m", strtotime($date)); // 12
$array['incomeyear'] = date("Y", strtotime($date)); // 2024
```

### File Deletion Logic
```php
if(config_item('demo') == FALSE) {
    if(file_exists(FCPATH.'uploads/images/'.$income->file)) {
        unlink(FCPATH.'uploads/images/'.$income->file);
    }
}
```
- Uses `FCPATH` constant for path
- Demo mode prevents file deletion (for testing)

### Audit Trail
- Captures: userID, usertypeID (no username field like Expense)
- Create: stores create_date
- Edit: updates userID fields (no modify_date)

### School Year Validation (same as Expense)
```php
$date = strtotime($this->input->post('date'));
$startdate = strtotime($this->data['schoolyearsessionobj']->startingdate);
$endingdate = strtotime($this->data['schoolyearsessionobj']->endingdate);
if(($date < $startdate) || ($date > $endingdate)) {
    return FALSE; // Validation fails
}
```

### getAllUserObjectWithStudentRelation()
- Helper to get all users with student relations
- Used for displaying creator in index view
- Scoped to schoolyearID and schoolID

### Performance Considerations
- File upload blocking (synchronous)
- File deletion checked on every delete (even if no file)
- No file size streaming

### Common Income Sources
- Donations, Grants, Fundraising events, Facility rentals, Book sales, Uniform sales, Miscellaneous revenue

