# Feature: Manage_salary

## Overview
**Controller**: `mvc/controllers/Manage_salary.php`  
**Primary Purpose**: Assigns salary templates (monthly or hourly) to individual users and displays salary breakdowns.  
**User Roles**: Admins, HR Staff, Superadmin  
**Status**: ✅ Active

## Functionality

### Core Features
- Assign salary templates to users by role
- Choose between monthly (Salary_template) or hourly (Hourly_template) pay type
- View user salary details with gross/net calculations
- Generate PDF salary slips
- Email salary slips to users
- Filter users by role (systemadmin, teacher, custom users)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `manage_salary/index/{usertypeID}` | List users by role with salary status | `manage_salary` |
| `add()` | `manage_salary/add/{userID}/{usertypeID}` | Assign salary to user | `manage_salary` |
| `edit()` | `manage_salary/edit/{userID}/{usertypeID}` | Edit user salary assignment | `manage_salary` |
| `view()` | `manage_salary/view/{userID}/{usertypeID}` | View salary details | `manage_salary_view` |
| `print_preview()` | `manage_salary/print_preview/{userID}/{usertypeID}` | Generate PDF salary slip | `manage_salary_view` |
| `send_mail()` | AJAX POST | Email salary slip | `manage_salary_view` |
| `delete()` | `manage_salary/delete/{userID}/{usertypeID}` | Remove salary assignment | `manage_salary` |
| `role_list()` | AJAX POST | Navigate to role-filtered index | - |
| `templatecall()` | AJAX POST | Get templates by salary type | - |

## Data Layer

### Models Used
- `manage_salary_m`: Salary assignment CRUD
- `salary_template_m`, `hourly_template_m`: Template data
- `salaryoption_m`: Allowances/deductions
- `systemadmin_m`, `teacher_m`, `user_m`: User data by type
- `usertype_m`: Role information

### Database Tables
- `manage_salary`: `manage_salaryID`, `userID`, `usertypeID`, `salary` (1=monthly, 2=hourly), `template` (templateID), `schoolID`, timestamps, `create_userID`, `create_username`, `create_usertype`
- Related: `salary_template`, `hourly_template`, `salaryoption`

## Validation Rules
- **salary**: Required, not '0' (1=monthly, 2=hourly)
- **template**: Required, not '0' (template ID)

## Dependencies & Interconnections

### Depends On (Upstream)
- **Salary_template**: Monthly salary templates
- **Hourly_template**: Hourly rate templates
- **User/Teacher/Systemadmin**: User records

### Used By (Downstream)
- **Salaryreport**: Generates salary reports
- **Overtime**: Calculates overtime pay using assigned templates

### Related Features
- **Salary_template**: Template definitions
- **Hourly_template**: Hourly template definitions
- **Overtime**: Overtime tracking

## User Flows

### Primary Flow: Assign Salary to User
1. Admin navigates to Manage_salary, selects role filter
2. System lists users of that role, highlights those with/without salary
3. Admin clicks "Add" on user without salary
4. Selects salary type: 1=Monthly or 2=Hourly
5. System loads matching templates (Salary_template or Hourly_template)
6. Selects template from dropdown
7. Submits form
8. System creates manage_salary record linking user to template
9. Redirects to index

### View Salary Slip
1. Click "View" on user with assigned salary
2. System retrieves user, template, and options (if monthly)
3. Calculates:
   - Gross Salary = Basic + Allowances
   - Total Deductions = Sum of deductions
   - Net Salary = Gross - Deductions
4. Displays breakdown with user info
5. Options to print PDF or email

## Edge Cases & Limitations
- **One Salary Per User**: Can't assign multiple templates
- **Role-Based Filtering**: Index requires role selection (0 shows blank)
- **User Table Routing**: Different tables for type 1 (systemadmin), 2 (teacher), others (user)
- **Hourly Salary Display**: Just shows hourly rate, no gross/net calc
- **No Effective Dates**: Assignment is current, no history or future dating
- **Template Changes**: Changing template doesn't affect old salary slips/reports

## Configuration
- Scoped by `schoolID`
- Uses select2 for dropdowns

## Notes for AI Agents

### Implementation Details
- **Salary Type**: 1=monthly (salary_template), 2=hourly (hourly_template)
- **Dynamic Templates**: AJAX `templatecall()` loads appropriate templates based on salary type
- **Calculation Logic**: Monthly salary has complex allowance/deduction calc, hourly just shows rate
- **User ID Fields**: systemadminID, teacherID, or userID depending on usertypeID
- **Audit Fields**: Stores create_username and create_usertype (unusual, normally just IDs)

### Business Logic
- **Permission**: `manage_salary` for assign/edit/delete, `manage_salary_view` for view/PDF/email
- **Duplicate Check**: Add route checks if manage_salary record exists for user
- **Template Validation**: No check if template exists/deleted before saving

### Performance
- Index lists all users of selected role (no pagination)
- View has complex calculation for monthly salaries
- PDF generation for salary slips

### Common Pitfalls
- **Template Deletion**: No cascade check if template deleted while assigned
- **User Type 0**: Invalid usertype causes errors (no handling)
- **Hourly Net Salary**: Set to hourly_rate, not calculated from hours worked
