# Feature: Cli

## Overview
**Controller**: `mvc/controllers/Cli.php`  
**Primary Purpose**: Command-line interface tools for running maintenance tasks and cron jobs  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing cli data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | cli/index | cli | List all records |
| `add()` | cli/add | cli_add | Create new record |
| `edit()` | cli/edit/{id}` | cli_edit | Update record |
| `view()` | cli/view/{id}` | cli_view | View details |
| `delete()` | cli/delete/{id}` | cli_delete | Delete record |

## Data Layer
- `cli` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Command-line interface tools for running maintenance tasks and cron jobs
