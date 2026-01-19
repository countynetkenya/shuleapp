# Feature: Overtime

## Overview
**Controller**: `mvc/controllers/Overtime.php`  
**Primary Purpose**: Tracks overtime hours worked by staff with automatic payment calculation based on assigned salary templates.  
**User Roles**: Admins, HR Staff, Superadmin  
**Status**: ✅ Active

## Functionality

### Core Features
- Record overtime hours for staff by date
- Automatic overtime amount calculation using salary template overtime_rate
- Filter users to only those with monthly salarymanagement (hourly staff excluded)
- List all overtime records with user and amount details
- Edit/delete overtime entries

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `overtime/index` | List all overtime records | `overtime` |
| `add()` | `overtime/add` | Create overtime record | `overtime` |
| `edit()` | `overtime/edit/{id}` | Edit overtime record | `overtime` |
| `delete()` | `overtime/delete/{id}` | Delete overtime record | `overtime` |
| `userscall()` | AJAX POST | Get users with monthly salary by role | - |
| `get_overtime_amount()` | AJAX POST | Calculate overtime amount preview | - |

## Data Layer

### Models Used
- `overtime_m`: Overtime record CRUD
- `manage_salary_m`: To get user salary assignments
- `salary_template_m`: To get overtime_rate
- `user_m`: User data access via dynamic tables
- `usertype_m`: Role information

### Database Tables
- `overtime`: `id`, `date`, `hours`, `amount` (overtime_rate), `total_amount` (hours * rate), `userID`, `user_table` (systemadmin/teacher/user), `usertypeID`, `schoolID`, timestamps, `create_userID`, `create_usertypeID`

## Validation Rules
- **roleId**: Required, numeric, not '0'
- **userId**: Required, numeric, not '0'
- **overtime_date**: Required, dd-mm-yyyy HH:MM:SS format (datetimepicker)
- **overtime_hours**: Required, numeric, max 10

## Dependencies & Interconnections

### Depends On (Upstream)
- **Manage_salary**: Users must have monthly salary (salary=1) assigned
- **Salary_template**: Uses overtime_rate field for calculation

### Used By (Downstream)
- **Salaryreport**: May include overtime payments
- **HR Reports**: Overtime hours tracking

### Related Features
- **Manage_salary**: Determines overtime eligibility and rate
- **Salary_template**: Provides overtime hourly rate

## User Flows

### Primary Flow: Record Overtime
1. Admin navigates to Overtime → Add
2. Selects role (systemadmin, teacher, or custom)
3. AJAX loads users of that role who have monthly salary assigned
4. Selects user from filtered dropdown
5. Enters overtime date/time
6. Enters hours worked (e.g., 3.5)
7. System calculates: total_amount = hours * overtime_rate
8. Saves record with calculated amounts
9. Redirects to index

### Calculation Logic
1. `hourCalculation()` method retrieves:
   - manage_salary record for user
   - salary_template linked to that assignment
   - overtime_rate from template
2. Calculates: total_amount = hours * overtime_rate
3. Returns object with amount (rate) and total_amount

## Edge Cases & Limitations
- **Monthly Salary Only**: Only users with `salary=1` (monthly) eligible (hourly staff excluded)
- **No Approval Workflow**: Overtime auto-approved on save
- **No Date Range**: Single date/time per record, no recurring overtime
- **Template Rate**: Uses current template's overtime_rate (no historical rate locking)
- **Table Name Hardcoded**: `tablename` array maps usertypeID to table names (1=systemadmin, 2=teacher, 3=user)

## Configuration
- Uses datetimepicker for date/time selection
- Scoped by `schoolID`

## Notes for AI Agents

### Implementation Details
- **Table Mapping**: `$tablename` array maps 1→systemadmin, 2→teacher, 3→user (default)
- **AJAX-Driven UX**: User dropdown populated via AJAX after role selection
- **Filter Logic**: `userscall()` uses `pluck_multi_array_key()` to filter users with salary=1
- **Real-Time Calc**: `get_overtime_amount()` provides preview before save
- **Date Format**: Frontend datetimepicker, backend expects dd-mm-yyyy HH:MM:SS, converts to Y-m-d H:i:s

### Business Logic
- **Permission**: `overtime` for all operations
- **Eligibility Check**: Frontend filters by manage_salary.salary=1, backend doesn't validate
- **Amount Storage**: Stores both overtime_rate and total_amount (denormalization for reporting)

### Performance
- Index lists all overtime records (no pagination)
- AJAX userscall could be slow if many users with salaries

### Common Pitfalls
- **Template Changes**: If overtime_rate changes in template, old records still show old rate
- **No Validation**: Doesn't verify manage_salary exists before calculation (assumes filtered correctly)
- **Hourly Staff**: Can't record overtime for hourly workers (by design)
