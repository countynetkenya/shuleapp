# Feature: Mobileapp

## Overview
**Controller**: `mvc/controllers/Mobileapp.php`  
**Primary Purpose**: API endpoints and mobile app integration for iOS/Android applications  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing mobileapp data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | mobileapp/index | mobileapp | List all records |
| `add()` | mobileapp/add | mobileapp_add | Create new record |
| `edit()` | mobileapp/edit/{id}` | mobileapp_edit | Update record |
| `view()` | mobileapp/view/{id}` | mobileapp_view | View details |
| `delete()` | mobileapp/delete/{id}` | mobileapp_delete | Delete record |

## Data Layer
- `mobileapp` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- API endpoints and mobile app integration for iOS/Android applications
