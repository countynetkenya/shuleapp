# Feature: App

## Overview
**Controller**: `mvc/controllers/App.php`  
**Primary Purpose**: Mobile app settings and configuration for connecting native apps to the system  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing app data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | app/index | app | List all records |
| `add()` | app/add | app_add | Create new record |
| `edit()` | app/edit/{id}` | app_edit | Update record |
| `view()` | app/view/{id}` | app_view | View details |
| `delete()` | app/delete/{id}` | app_delete | Delete record |

## Data Layer
- `app` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Mobile app settings and configuration for connecting native apps to the system
