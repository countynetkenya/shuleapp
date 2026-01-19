# Feature: Hostel

## Overview
**Controller**: `mvc/controllers/Hostel.php`  
**Primary Purpose**: Hostel/dormitory management for boarding students with room assignments and warden details  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing hostel data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | hostel/index | hostel | List all records |
| `add()` | hostel/add | hostel_add | Create new record |
| `edit()` | hostel/edit/{id}` | hostel_edit | Update record |
| `view()` | hostel/view/{id}` | hostel_view | View details |
| `delete()` | hostel/delete/{id}` | hostel_delete | Delete record |

## Data Layer
- `hostel` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Hostel/dormitory management for boarding students with room assignments and warden details
