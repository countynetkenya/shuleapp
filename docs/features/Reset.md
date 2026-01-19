# Feature: Reset

## Overview
**Controller**: `mvc/controllers/Reset.php`  
**Primary Purpose**: Account reset utility for administrators to reset user accounts or settings  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing reset data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | reset/index | reset | List all records |
| `add()` | reset/add | reset_add | Create new record |
| `edit()` | reset/edit/{id}` | reset_edit | Update record |
| `view()` | reset/view/{id}` | reset_view | View details |
| `delete()` | reset/delete/{id}` | reset_delete | Delete record |

## Data Layer
- `reset` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Account reset utility for administrators to reset user accounts or settings
