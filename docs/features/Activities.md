# Feature: Activities

## Overview
**Controller**: `mvc/controllers/Activities.php`  
**Primary Purpose**: Extracurricular activities management (clubs, sports teams) with student participation tracking  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing activities data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | activities/index | activities | List all records |
| `add()` | activities/add | activities_add | Create new record |
| `edit()` | activities/edit/{id}` | activities_edit | Update record |
| `view()` | activities/view/{id}` | activities_view | View details |
| `delete()` | activities/delete/{id}` | activities_delete | Delete record |

## Data Layer
- `activities` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Extracurricular activities management (clubs, sports teams) with student participation tracking
