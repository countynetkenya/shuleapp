# Feature: Fonlineadmission

## Overview
**Controller**: `mvc/controllers/Fonlineadmission.php`  
**Primary Purpose**: Frontend online admission form for public applicants to submit admission requests  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing fonlineadmission data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | fonlineadmission/index | fonlineadmission | List all records |
| `add()` | fonlineadmission/add | fonlineadmission_add | Create new record |
| `edit()` | fonlineadmission/edit/{id}` | fonlineadmission_edit | Update record |
| `view()` | fonlineadmission/view/{id}` | fonlineadmission_view | View details |
| `delete()` | fonlineadmission/delete/{id}` | fonlineadmission_delete | Delete record |

## Data Layer
- `fonlineadmission` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Frontend online admission form for public applicants to submit admission requests
