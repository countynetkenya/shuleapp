# Feature: Onlineadmission

## Overview
**Controller**: `mvc/controllers/Onlineadmission.php`  
**Primary Purpose**: Admin management of online admission applications with approve/reject workflow  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing onlineadmission data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | onlineadmission/index | onlineadmission | List all records |
| `add()` | onlineadmission/add | onlineadmission_add | Create new record |
| `edit()` | onlineadmission/edit/{id}` | onlineadmission_edit | Update record |
| `view()` | onlineadmission/view/{id}` | onlineadmission_view | View details |
| `delete()` | onlineadmission/delete/{id}` | onlineadmission_delete | Delete record |

## Data Layer
- `onlineadmission` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Admin management of online admission applications with approve/reject workflow
