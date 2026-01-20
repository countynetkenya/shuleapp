# Feature: Transport

## Overview
**Controller**: `mvc/controllers/Transport.php`  
**Primary Purpose**: School bus/transport route management with student assignments and driver information  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing transport data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | transport/index | transport | List all records |
| `add()` | transport/add | transport_add | Create new record |
| `edit()` | transport/edit/{id}` | transport_edit | Update record |
| `view()` | transport/view/{id}` | transport_view | View details |
| `delete()` | transport/delete/{id}` | transport_delete | Delete record |

## Data Layer
- `transport` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- School bus/transport route management with student assignments and driver information
