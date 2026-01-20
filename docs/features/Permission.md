# Feature: Permission

## Overview
**Controller**: `mvc/controllers/Permission.php`  
**Primary Purpose**: Maps permissions to usertypes, defining granular access control for each role via module-based permissions.  
**User Roles**: Admin  
**Status**: ✅ Active

## Functionality

### Core Features
- Display permission modules and individual permissions for a usertype
- Bulk update all permissions for a usertype
- Multi-select checkbox interface
- School-scoped permission assignments

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /permission/index | Display permission selection page (no usertype) | Admin_Controller |
| GET | /permission/index/{usertypeID} | Display permissions for specific usertype | Admin_Controller |
| POST | /permission/permission_list | AJAX endpoint to navigate to usertype | Admin_Controller |
| POST | /permission/save/{usertypeID} | Bulk save permissions for usertype | Admin_Controller |

## Data Layer

### Models Used
- `permission_m`: Permission CRUD and relationship management
- `usertype_m`: Usertype validation

### Database Tables
- `permission`: permissionID (PK), module name, permission name, description
- `permission_relationships`: permission_id, usertype_id, schoolID (composite PK)
- `usertype`: Referenced for validation

## Validation Rules
- No form validation; operates on $_POST array directly
- usertypeID must exist in usertype table

## Dependencies & Interconnections

### Depends On (Upstream)
- **Usertype**: Permissions assigned to usertypeID
- **School**: Permissions scoped to schoolID

### Used By (Downstream)  
- **All Controllers**: permissionChecker() function validates permissions
- **User**: User actions gated by permission checks
- **Menu**: Menu items filtered by permissions

### Related Features
- **Usertype**: Each usertype has permission set
- **School**: Auto-creates 500+ permission relationships on school creation
- **Menu**: Menu visibility driven by permissions

## User Flows

### Primary Flow: Assign Permissions to Role
1. Admin navigates to /permission/index
2. Selects usertype from dropdown
3. System redirects to /permission/index/{usertypeID}
4. Displays module-grouped checkboxes (e.g., Student module: view, add, edit, delete)
5. Admin selects/deselects permissions
6. Clicks Save
7. System deletes all existing permission_relationships for usertype+schoolID
8. Inserts new relationships based on checked permissions
9. Redirects back to /permission/index/{usertypeID}

## Edge Cases & Limitations
- Delete-then-insert pattern (not update); race condition possible
- No validation of $_POST data (assumes all values are permissionIDs)
- No confirmation dialog before bulk permission change
- If save fails mid-operation, usertype may have no permissions
- No permission inheritance or grouping (flat structure)

## Configuration
- Permissions defined in `permission` table
- Default permission sets created during school creation (School controller, lines 125-948)

## Notes for AI Agents
- **Bulk Replace**: save() deletes ALL permission_relationships for usertype+schoolID, then inserts new ones; not transactional in controller
- **No Validation**: $_POST values directly inserted; ensure frontend sends only valid permissionIDs
- **Module Grouping**: get_modules_with_permission() groups permissions by module for UI display
- **permissionChecker()**: Global helper function (likely in Admin_Controller or helper) validates user permissions
- **Optimization**: For usertypes with 100+ permissions, UI can be slow; consider batch operations
- **School Isolation**: Multi-tenancy via schoolID in permission_relationships

