# Feature: Instruction

## Overview
**Controller**: `mvc/controllers/Instruction.php`  
**Primary Purpose**: System instructions/help documentation for users on how to use features  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing instruction data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | instruction/index | instruction | List all records |
| `add()` | instruction/add | instruction_add | Create new record |
| `edit()` | instruction/edit/{id}` | instruction_edit | Update record |
| `view()` | instruction/view/{id}` | instruction_view | View details |
| `delete()` | instruction/delete/{id}` | instruction_delete | Delete record |

## Data Layer
- `instruction` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- System instructions/help documentation for users on how to use features
