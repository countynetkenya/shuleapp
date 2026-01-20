# Feature: Leavecategory

## Overview
**Controller**: `mvc/controllers/Leavecategory.php`  
**Primary Purpose**: Manages leave category types (e.g., Sick Leave, Casual Leave, Annual Leave) available for the school.  
**User Roles**: Admins, HR Staff, Superadmin  
**Status**: ✅ Active

## Functionality

### Core Features
- Create leave categories (sick, casual, annual, maternity, etc.)
- List all leave categories for school
- Edit category names
- Delete unused categories
- Ensures unique category names per school

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| `index()` | `leavecategory/index` | List all leave categories | `leavecategory` |
| `add()` | `leavecategory/add` | Create new category | `leavecategory` |
| `edit()` | `leavecategory/edit/{leavecategoryID}` | Edit category name | `leavecategory` |
| `delete()` | `leavecategory/delete/{leavecategoryID}` | Delete category | `leavecategory` |

## Data Layer

### Models Used
- `leavecategory_m`: Category CRUD operations

### Database Tables
- `leavecategory`: `leavecategoryID`, `leavecategory` (name), `leavegender` (1=all, deprecated field), `schoolID`, `create_date`, `modify_date`, `create_userID`, `create_usertypeID`

## Validation Rules
- **leavecategory**: Required, max 255 chars, must be unique per school

### Custom Validations
- **unique_leavecategory**: Prevents duplicate names within same school (case-sensitive)

## Dependencies & Interconnections

### Depends On (Upstream)
- **School**: Categories scoped to school

### Used By (Downstream)
- **Leaveassign**: References categories for quota assignment
- **Leaveapply**: Dropdown selection for applications
- **Leaveapplication**: Display category name in applications
- **Reports**: Leave reports grouped by category

### Related Features
- **Leaveassign**: Assigns quotas per category
- **Leaveapplication/Leaveapply**: Use categories for applications

## User Flows

### Primary Flow: Create Leave Category
1. Admin navigates to Leavecategory → Add
2. Enters category name (e.g., "Sick Leave")
3. System validates uniqueness
4. Creates category with `leavegender=1` (hardcoded, unused)
5. Redirects to index

### Edit Flow
1. Admin views category list
2. Clicks edit on category
3. Changes name
4. System validates uniqueness (excluding current record)
5. Updates category
6. Redirects to index

### Delete Flow
1. Admin clicks delete
2. System verifies category exists for school
3. Deletes without checking for dependent records
4. Redirects to index

## Edge Cases & Limitations
- **No Cascade Delete Check**: Deleting category doesn't check for Leaveassign or Leaveapplication references
- **Gender Field Unused**: `leavegender` always set to 1, no UI or logic for it
- **Simple Structure**: Just a name, no description, color coding, or metadata
- **No Archiving**: Delete is permanent (no soft delete)

## Configuration
- Filtered by `schoolID` (multi-tenant safe)

## Notes for AI Agents

### Implementation Details
- **Minimal Controller**: Very simple CRUD, no complex logic
- **Hardcoded Gender**: `leavegender` field exists but not configurable (legacy field)
- **Timestamps**: Auto-managed create_date and modify_date
- **Audit Trail**: Stores create_userID and create_usertypeID

### Business Logic
- **Permission**: `leavecategory` required for all operations
- **School Scoping**: All queries filtered by `schoolID`
- **Case-Sensitive Names**: "Sick Leave" ≠ "sick leave" (database collation dependent)

### Performance
- Simple queries, no joins
- No pagination on index (could be issue with 100+ categories)

### Common Pitfalls
- **Deleting In-Use Category**: No warning if leaveassign/leaveapplication records reference it
- **Long Names**: Max 255 chars not validated on frontend

