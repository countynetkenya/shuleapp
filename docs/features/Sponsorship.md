# Feature: Sponsorship

## Overview
**Controller**: `mvc/controllers/Sponsorship.php`  
**Primary Purpose**: Student sponsorship program linking sponsors to students with payment tracking  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing sponsorship data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | sponsorship/index | sponsorship | List all records |
| `add()` | sponsorship/add | sponsorship_add | Create new record |
| `edit()` | sponsorship/edit/{id}` | sponsorship_edit | Update record |
| `view()` | sponsorship/view/{id}` | sponsorship_view | View details |
| `delete()` | sponsorship/delete/{id}` | sponsorship_delete | Delete record |

## Data Layer
- `sponsorship` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Student sponsorship program linking sponsors to students with payment tracking
