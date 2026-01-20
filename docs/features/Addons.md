# Feature: Addons

## Overview
**Controller**: `mvc/controllers/Addons.php`  
**Primary Purpose**: Plugin/addon management system for installing additional modules or features  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing addons data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | addons/index | addons | List all records |
| `add()` | addons/add | addons_add | Create new record |
| `edit()` | addons/edit/{id}` | addons_edit | Update record |
| `view()` | addons/view/{id}` | addons_view | View details |
| `delete()` | addons/delete/{id}` | addons_delete | Delete record |

## Data Layer
- `addons` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Plugin/addon management system for installing additional modules or features
