# Feature: Privacy

## Overview
**Controller**: `mvc/controllers/Privacy.php`  
**Primary Purpose**: Privacy policy and terms display for compliance with data protection regulations  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing privacy data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | privacy/index | privacy | List all records |
| `add()` | privacy/add | privacy_add | Create new record |
| `edit()` | privacy/edit/{id}` | privacy_edit | Update record |
| `view()` | privacy/view/{id}` | privacy_view | View details |
| `delete()` | privacy/delete/{id}` | privacy_delete | Delete record |

## Data Layer
- `privacy` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Privacy policy and terms display for compliance with data protection regulations
