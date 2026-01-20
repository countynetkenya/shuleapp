# Feature: Childcare

## Overview
**Controller**: `mvc/controllers/Childcare.php`  
**Primary Purpose**: Early childhood/daycare module for managing younger students with special care requirements  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing childcare data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | childcare/index | childcare | List all records |
| `add()` | childcare/add | childcare_add | Create new record |
| `edit()` | childcare/edit/{id}` | childcare_edit | Update record |
| `view()` | childcare/view/{id}` | childcare_view | View details |
| `delete()` | childcare/delete/{id}` | childcare_delete | Delete record |

## Data Layer
- `childcare` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Early childhood/daycare module for managing younger students with special care requirements
