# Feature: Candidate

## Overview
**Controller**: `mvc/controllers/Candidate.php`  
**Primary Purpose**: Admission candidate management for prospective students before enrollment  
**User Roles**: Admin, Superadmin (varies by feature)  
**Status**: ✅ Active

## Functionality
Basic CRUD operations for managing candidate data with list, add, edit, view, and delete functions.

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | candidate/index | candidate | List all records |
| `add()` | candidate/add | candidate_add | Create new record |
| `edit()` | candidate/edit/{id}` | candidate_edit | Update record |
| `view()` | candidate/view/{id}` | candidate_view | View details |
| `delete()` | candidate/delete/{id}` | candidate_delete | Delete record |

## Data Layer
- `candidate` table - Primary data storage
- Related tables as needed for foreign keys

## Notes for AI Agents
- Standard CRUD controller following school management system patterns
- School-scoped data (filtered by schoolID)
- Permission-based access control
- Admission candidate management for prospective students before enrollment
