# Feature: Resetpassword

## Overview
**Controller**: `mvc/controllers/Resetpassword.php`  
**Primary Purpose**: Password reset functionality for users who forgot their passwords  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing resetpassword data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | resetpassword/index | resetpassword | List all records |
| `add()` | resetpassword/add | resetpassword_add | Create new record |
| `edit()` | resetpassword/edit/{id}` | resetpassword_edit | Update record |
| `view()` | resetpassword/view/{id}` | resetpassword_view | View details |
| `delete()` | resetpassword/delete/{id}` | resetpassword_delete | Delete record |

## Data Layer
- `resetpassword` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Password reset functionality for users who forgot their passwords
