# Feature: Salary_template

## Overview
**Controller**: `mvc/controllers/Salary_template.php`  
**Primary Purpose**: Manages salary grade templates with basic salary, overtime rate, allowances, and deductions for monthly-paid staff.  
**User Roles**: Admins, HR Staff, Superadmin  
**Status**: ✅ Active

## Functionality

### Core Features
- Create salary templates with grade names (e.g., "Grade A", "Senior Staff")
- Define basic salary amount and overtime hourly rate
- Add multiple allowances (housing, transport, medical, etc.) with amounts
- Add multiple deductions (tax, insurance, loan, etc.) with amounts
- Calculate gross salary and net salary automatically
- View template details with salary breakdown
- Edit templates with dynamic allowance/deduction management
- Delete templates

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `salary_template/index` | List all salary templates | `salary_template` |
| `add()` | `salary_template/add` | Display add form | `salary_template` |
| `add_ajax()` | AJAX POST | Create template with options | `salary_template` |
| `edit()` | `salary_template/edit/{templateID}` | Display edit form | `salary_template` |
| `edit_ajax()` | AJAX POST | Update template | `salary_template` |
| `view()` | `salary_template/view/{templateID}` | View template details | `salary_template` |
| `delete()` | `salary_template/delete/{templateID}` | Delete template | `salary_template` |

## Data Layer

### Models Used
- `salary_template_m`: Template CRUD
- `salaryoption_m`: Allowances and deductions linked to templates

### Database Tables
- `salary_template`: `salary_templateID`, `salary_grades` (name), `basic_salary`, `overtime_rate`, `schoolID`
- `salaryoption`: `salary_optionID`, `salary_templateID`, `option_type` (1=allowance, 2=deduction), `label_name`, `label_amount`, `schoolID`

## Validation Rules
- **salary_grades**: Required, max 128 chars, unique per school
- **basic_salary**: Required, max 11 chars (numeric implied)
- **overtime_rate**: Required, max 11 chars (numeric for hourly rate)

## Dependencies & Interconnections

### Depends On (Upstream)
- None (foundational configuration)

### Used By (Downstream)
- **Manage_salary**: Assigns templates to users
- **Overtime**: Uses overtime_rate for hour calculations
- **Salaryreport**: Generates salary reports from templates

### Related Features
- **Hourly_template**: Alternative salary model (hourly workers)
- **Manage_salary**: Links users to salary templates
- **Overtime**: Overtime pay calculation

## User Flows

### Primary Flow: Create Salary Template
1. Admin navigates to Salary_template → Add
2. Enters salary grade name (e.g., "Grade A - Teachers")
3. Enters basic monthly salary (e.g., 50000)
4. Enters overtime hourly rate (e.g., 300)
5. Adds allowances dynamically:
   - Clicks "Add Allowance" button
   - Enters label (e.g., "House Allowance") and amount (e.g., 10000)
   - Repeats for multiple allowances
6. Adds deductions dynamically:
   - Clicks "Add Deduction" button
   - Enters label (e.g., "NHIF") and amount (e.g., 1500)
   - Repeats for multiple deductions
7. Submits via AJAX
8. System calculates: Gross = Basic + AllowancesTotal, Net = Gross - DeductionsTotal
9. Returns success, redirects to index

### View Template
1. Click template name in index
2. System displays:
   - Grade name, basic salary, overtime rate
   - List of allowances with amounts
   - List of deductions with amounts
   - Gross Salary (basic + allowances)
   - Total Deductions
   - Net Salary
3. Shows financial breakdown

### Edit Template
1. Click edit on template
2. System loads template and all salaryoptions (ordered by ID)
3. Displays current allowances and deductions
4. User modifies basic/overtime, adds/removes allowances/deductions
5. On submit, deletes ALL existing salaryoptions
6. Re-inserts new allowances/deductions
7. Recalculates and saves

## Edge Cases & Limitations
- **Delete and Replace**: Edit deletes all options then reinserts (not UPDATE)
- **No Historical Tracking**: Changes don't preserve history
- **In-Use Templates**: No check if template assigned to users before deletion
- **Allowance/Deduction Limits**: No max count validation (could add 100s)
- **Amount Validation**: No min/max validation on amounts
- **Dynamic Fields**: Frontend JavaScript manages allowance/deduction count
- **AJAX-Only Submit**: Add/edit forms use AJAX, not traditional POST

## Configuration
- Scoped by `schoolID`

## Notes for AI Agents

### Implementation Details
- **Option Types**: 1=Allowance (adds to salary), 2=Deduction (subtracts)
- **Dynamic Form Fields**: JavaScript adds numbered fields (allowanceslabel1, allowancesamount1, etc.)
- **Batch Insert**: Loops `allowances_number` and `deductions_number` to insert multiple options
- **Delete-Replace Pattern**: Edit doesn't update existing options, deletes all then reinserts
- **Calculation**: Gross = basic_salary + SUM(allowances), Net = Gross - SUM(deductions)

### Business Logic
- **Permission**: `salary_template` for all operations
- **School Scoping**: Templates isolated by schoolID
- **Unique Grades**: Prevents duplicate grade names per school

### Performance
- AJAX-driven interface for better UX
- Batch insert efficient for multiple options
- Delete-replace on edit could be optimized to UPDATE existing

### Common Pitfalls
- **Option Deletion**: Orphaned salaryoptions if template deleted (cascade delete needed)
- **Amount Type**: label_amount stored as numeric, ensure validation
- **Grade Name Collision**: Unique check is case-sensitive

