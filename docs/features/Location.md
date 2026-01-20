# Feature: Location

## Overview
**Controller**: `mvc/controllers/Location.php`  
**Primary Purpose**: Physical location/room management (classrooms, labs, offices) used by Asset and other modules  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing location data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | location/index | location | List all records |
| `add()` | location/add | location_add | Create new record |
| `edit()` | location/edit/{id}` | location_edit | Update record |
| `view()` | location/view/{id}` | location_view | View details |
| `delete()` | location/delete/{id}` | location_delete | Delete record |

## Data Layer
- `location` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Physical location/room management (classrooms, labs, offices) used by Asset and other modules
