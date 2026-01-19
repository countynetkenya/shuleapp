# Feature: Remark

## Overview
**Controller**: `mvc/controllers/Remark.php`  
**Primary Purpose**: Student remarks/notes system for recording behavioral notes, achievements, or concerns  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing remark data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | remark/index | remark | List all records |
| `add()` | remark/add | remark_add | Create new record |
| `edit()` | remark/edit/{id}` | remark_edit | Update record |
| `view()` | remark/view/{id}` | remark_view | View details |
| `delete()` | remark/delete/{id}` | remark_delete | Delete record |

## Data Layer
- `remark` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Student remarks/notes system for recording behavioral notes, achievements, or concerns
