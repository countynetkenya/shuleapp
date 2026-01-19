# Feature: Activitiescategory

## Overview
**Controller**: `mvc/controllers/Activitiescategory.php`  
**Primary Purpose**: Activity categories (Sports, Arts, Music, etc.) for organizing extracurricular activities  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing activitiescategory data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | activitiescategory/index | activitiescategory | List all records |
| `add()` | activitiescategory/add | activitiescategory_add | Create new record |
| `edit()` | activitiescategory/edit/{id}` | activitiescategory_edit | Update record |
| `view()` | activitiescategory/view/{id}` | activitiescategory_view | View details |
| `delete()` | activitiescategory/delete/{id}` | activitiescategory_delete | Delete record |

## Data Layer
- `activitiescategory` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Activity categories (Sports, Arts, Music, etc.) for organizing extracurricular activities
