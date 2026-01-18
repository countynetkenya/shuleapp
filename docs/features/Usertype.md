# Feature: Usertype

## Overview
**Controller**: `mvc/controllers/Usertype.php`  
**Primary Purpose**: Manages custom user role definitions allowing schools to create role-based access control beyond default types.  
**User Roles**: Admin  
**Status**: ✅ Active

## Functionality

### Core Features
- Create custom user roles/types
- Edit existing usertype names
- Delete custom usertypes
- School-scoped usertypes (multi-tenant support)

### Routes & Methods
| Method | Route | Purpose | Permission |
|--------|-------|---------|------------|
| GET | /usertype/index | List all usertypes for active school | Admin_Controller |
| GET | /usertype/add | Display add usertype form | Admin_Controller |
| POST | /usertype/add | Create new usertype | Admin_Controller |
| GET | /usertype/edit/{id} | Display edit usertype form | Admin_Controller |
| POST | /usertype/edit/{id} | Update usertype name | Admin_Controller |
| GET | /usertype/delete/{id} | Delete usertype | Admin_Controller |

## Data Layer

### Models Used
- `usertype_m`: Usertype CRUD operations

### Database Tables
- `usertype`: usertypeID (PK), usertype (name), create_date, modify_date, create_userID, create_username, create_usertype, schoolID

## Validation Rules
- **usertype**: required, max_length[60], xss_clean, callback_unique_usertype

## Dependencies & Interconnections

### Depends On (Upstream)
- **School**: usertypes scoped to schoolID

### Used By (Downstream)  
- **User**: References usertypeID
- **Teacher**: Uses usertypeID (default 2)
- **Student**: Uses usertypeID (default 3)
- **Parents**: Uses usertypeID (default 4)
- **Permission**: Permission relationships mapped to usertypeID

### Related Features
- **Permission**: Each usertype has associated permission set
- **User**: User creation requires usertypeID selection

## User Flows

### Primary Flow: Create Custom Role
1. Admin navigates to /usertype/add
2. Enters usertype name (e.g., "Accountant", "Librarian")
3. System validates uniqueness within schoolID
4. Creates record with audit trail
5. Redirects to /usertype/index
6. Admin then assigns permissions via Permission controller

## Edge Cases & Limitations
- Default usertypes (1-4: Admin, Teacher, Student, Parent) cannot be deleted via this controller
- Usertype names must be unique per school
- No built-in validation to prevent deleting usertype in use

## Configuration
- Default usertypes created during install: Admin, Teacher, Student, Parent

## Notes for AI Agents
- **Default Usertypes**: IDs 1-4 are system defaults; custom types start at 5+
- **Audit Trail**: Records create_userID, create_username, create_usertype for tracking
- **Permission Pairing**: Creating a usertype is only half the setup; permissions must be assigned separately via Permission controller
- **Deletion Risk**: No cascade delete or usage check; deleting a usertype in use may cause FK constraint violations or orphaned users

