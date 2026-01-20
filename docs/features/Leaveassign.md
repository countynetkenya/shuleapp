# Feature: Leaveassign (Leave Assignment)

## Overview
**Controller**: `mvc/controllers/Leaveassign.php`  
**Primary Purpose**: Manages allocation of leave days per category for different user types within each school year.  
**User Roles**: Admins, HR Staff, Superadmin  
**Status**: ✅ Active

## Functionality

### Core Features
- Assign number of leave days per leave category and user type
- View all leave assignments for current school year
- Edit existing leave quotas
- Delete leave assignments
- Prevents duplicate assignments (unique category + usertype + schoolyear)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `leaveassign/index` | List all leave assignments | `leaveassign` |
| `add()` | `leaveassign/add` | Create new leave assignment | `leaveassign` |
| `edit()` | `leaveassign/edit/{leaveassignID}` | Edit leave assignment | `leaveassign` |
| `delete()` | `leaveassign/delete/{leaveassignID}` | Delete leave assignment | `leaveassign` |

## Data Layer

### Models Used
- `leaveassign_m`: Leave assignment CRUD
- `leavecategory_m`: Leave category definitions
- `usertype_m`: User type/role information

### Database Tables
- `leaveassign`: `leaveassignID`, `usertypeID`, `leavecategoryID`, `leaveassignday` (quota), `schoolyearID`, `schoolID`, `create_date`, `modify_date`, `create_userID`, `create_usertypeID`
- Related: `leavecategory`, `usertype`

## Validation Rules
- **usertypeID**: Required, not '0', unique with category+year
- **leavecategoryID**: Required, not '0', unique with usertype+year
- **leaveassignday**: Required, natural number > 0

### Custom Validations
- **unique_category**: Prevents duplicate (leavecategoryID + usertypeID + schoolyearID) combinations
- **unique_data**: Ensures dropdown selections are not '0' (placeholder)

## Dependencies & Interconnections

### Depends On (Upstream)
- **Leavecategory**: Must exist before assignment
- **Usertype**: Must have valid role
- **Schoolyear**: Assignments tied to academic year

### Used By (Downstream)
- **Leaveapply**: Displays available balance (quota - used)
- **Leaveapplication**: Shows remaining days for approval decision
- **Reports**: Leave quota vs usage analysis

### Related Features
- **Leavecategory**: Defines leave types for assignment
- **Leaveapplication/Leaveapply**: Consumes allocated quotas

## User Flows

### Primary Flow: Assign Leave Quota
1. Admin navigates to Leaveassign → Add
2. Selects user type (Teacher, Admin, Custom Role, etc.)
3. Selects leave category (Sick, Casual, etc.)
4. Enters number of days (e.g., 12 days sick leave)
5. System validates uniqueness (no duplicate category+type+year)
6. Saves assignment for current school year
7. Redirects to index

### Edit Flow
1. Admin views index of current assignments
2. Clicks edit on assignment
3. Changes user type, category, or day count
4. System re-validates uniqueness (excluding current record)
5. Updates assignment
6. Redirects to index

### Delete Flow
1. Admin clicks delete on assignment
2. System checks if assignment exists for current year
3. Deletes record (no cascade delete check for dependent applications)
4. Redirects to index

## Edge Cases & Limitations
- **No User-Level Assignment**: Quota is per user TYPE, not individual users
- **School Year Scoped**: Each year needs separate assignments (no rollover)
- **No Historical View**: Index shows only current year assignments
- **Deletion Impact**: Deleting assignment breaks leave balance calculation in Leaveapply/Leaveapplication
- **No Usage Tracking Here**: Doesn't show how many days used/remaining (calculated in other features)
- **Year Lock**: Can only add/edit for current year (except superadmin)

## Configuration
- Filtered by `schoolyearID` and `schoolID`
- Respects `school_year` setting for edit restrictions

## Notes for AI Agents

### Implementation Details
- **Unique Constraint**: `unique_category()` callback queries for duplicate with `!= leaveassignID` on edit
- **Natural Number Validation**: `is_natural_no_zero` ensures positive integer days
- **Select2 UI**: Uses select2 for dropdown enhancement
- **Simple CRUD**: No complex calculations, just assignment storage

### Business Logic
- **Permission**: `leaveassign` required for all operations
- **School Year Lock**: `school_year` setting must match or user must be superadmin
- **Cascade Delete**: No check for dependent `leaveapplication` records (could leave orphans)

### Performance
- Simple queries, no joins or complex calculations
- Index displays all assignments (no pagination)

### Common Pitfalls
- **Deleting In-Use Quota**: No warning if leave applications reference this assignment
- **Year Transitions**: Must manually recreate assignments for new school year
- **Usertype Changes**: If usertype deleted, assignment orphaned

