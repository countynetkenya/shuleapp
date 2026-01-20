# Feature: License

## Overview
**Controller**: `mvc/controllers/License.php`  
**Primary Purpose**: License key validation and activation system for software licensing  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing license data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | license/index | license | List all records |
| `add()` | license/add | license_add | Create new record |
| `edit()` | license/edit/{id}` | license_edit | Update record |
| `view()` | license/view/{id}` | license_view | View details |
| `delete()` | license/delete/{id}` | license_delete | Delete record |

## Data Layer
- `license` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- License key validation and activation system for software licensing
