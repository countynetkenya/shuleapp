# Feature: Hourly_template

## Overview
**Controller**: `mvc/controllers/Hourly_template.php`  
**Primary Purpose**: Manages hourly rate templates for part-time or hourly-paid staff (simpler than Salary_template, just grade name and hourly rate).  
**User Roles**: Admins, HR Staff, Superadmin  
**Status**: ✅ Active

## Functionality

### Core Features
- Create hourly rate templates with grade names
- Define hourly pay rate
- List all hourly templates
- Edit templates
- Delete templates
- Unique grade name validation

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `hourly_template/index` | List all hourly templates | `hourly_template` |
| `add()` | `hourly_template/add` | Create new template | `hourly_template` |
| `edit()` | `hourly_template/edit/{hourly_templateID}` | Edit template | `hourly_template` |
| `delete()` | `hourly_template/delete/{hourly_templateID}` | Delete template | `hourly_template` |

## Data Layer

### Models Used
- `hourly_template_m`: Template CRUD

### Database Tables
- `hourly_template`: `hourly_templateID`, `hourly_grades` (name), `hourly_rate`, `schoolID`

## Validation Rules
- **hourly_grades**: Required, max 128 chars, unique per school
- **hourly_rate**: Required, numeric, max 11 chars

## Dependencies & Interconnections

### Depends On (Upstream)
- None

### Used By (Downstream)
- **Manage_salary**: Assigns hourly templates to users (salary type 2)

### Related Features
- **Salary_template**: Alternative for monthly-paid staff
- **Manage_salary**: Links users to hourly templates

## User Flows

### Primary Flow: Create Hourly Template
1. Admin navigates to Hourly_template → Add
2. Enters grade name (e.g., "Part-Time Staff")
3. Enters hourly rate (e.g., 500)
4. Submits form
5. System validates and creates template
6. Redirects to index

## Edge Cases & Limitations
- **Simpler than Salary_template**: No allowances, deductions, or overtime rate
- **No In-Use Check**: Can delete template assigned to users
- **No Calculation Display**: Just stores rate, no salary preview

## Configuration
- Scoped by `schoolID`

## Notes for AI Agents

### Implementation Details
- Much simpler than Salary_template (just 2 fields)
- Traditional form POST (not AJAX like Salary_template)
- No related options table

### Business Logic
- **Permission**: `hourly_template` for all operations
- Unique constraint on hourly_grades per school

### Common Pitfalls
- Deleting template doesn't check Manage_salary references

