# Feature: Reminder

## Overview
**Controller**: `mvc/controllers/Reminder.php`  
**Primary Purpose**: Reminder/task management for creating notifications and to-do items with due dates  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing reminder data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | reminder/index | reminder | List all records |
| `add()` | reminder/add | reminder_add | Create new record |
| `edit()` | reminder/edit/{id}` | reminder_edit | Update record |
| `view()` | reminder/view/{id}` | reminder_view | View details |
| `delete()` | reminder/delete/{id}` | reminder_delete | Delete record |

## Data Layer
- `reminder` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Reminder/task management for creating notifications and to-do items with due dates
