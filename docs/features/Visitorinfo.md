# Feature: Visitorinfo

## Overview
**Controller**: `mvc/controllers/Visitorinfo.php`  
**Primary Purpose**: Visitor log system for tracking school visitors with check-in/out times and purpose  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing visitorinfo data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | visitorinfo/index | visitorinfo | List all records |
| `add()` | visitorinfo/add | visitorinfo_add | Create new record |
| `edit()` | visitorinfo/edit/{id}` | visitorinfo_edit | Update record |
| `view()` | visitorinfo/view/{id}` | visitorinfo_view | View details |
| `delete()` | visitorinfo/delete/{id}` | visitorinfo_delete | Delete record |

## Data Layer
- `visitorinfo` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Visitor log system for tracking school visitors with check-in/out times and purpose
