# Feature: Language

## Overview
**Controller**: `mvc/controllers/Language.php`  
**Primary Purpose**: Multi-language system management for translating UI and managing language packs  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing language data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | language/index | language | List all records |
| `add()` | language/add | language_add | Create new record |
| `edit()` | language/edit/{id}` | language_edit | Update record |
| `view()` | language/view/{id}` | language_view | View details |
| `delete()` | language/delete/{id}` | language_delete | Delete record |

## Data Layer
- `language` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Multi-language system management for translating UI and managing language packs
