# Feature: Sponsor

## Overview
**Controller**: `mvc/controllers/Sponsor.php`  
**Primary Purpose**: Sponsor/donor database for tracking individuals or organizations providing financial support  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing sponsor data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | sponsor/index | sponsor | List all records |
| `add()` | sponsor/add | sponsor_add | Create new record |
| `edit()` | sponsor/edit/{id}` | sponsor_edit | Update record |
| `view()` | sponsor/view/{id}` | sponsor_view | View details |
| `delete()` | sponsor/delete/{id}` | sponsor_delete | Delete record |

## Data Layer
- `sponsor` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Sponsor/donor database for tracking individuals or organizations providing financial support
