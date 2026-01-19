# Feature: Frontendmenu

## Overview
**Controller**: `mvc/controllers/Frontendmenu.php`  
**Primary Purpose**: Manage navigation menu structure for public website with hierarchy and ordering.  
**User Roles**: Admin, Superadmin  
**Status**: ✅ Active

## Functionality
- **Menu CRUD**: Create, edit, delete menu items
- **Hierarchical Structure**: Parent-child menu relationships
- **Link Types**: Internal pages, external URLs, custom links
- **Ordering**: Set display order
- **Active Status**: Show/hide menu items

## Routes & Methods
| Method | Route | Permission | Description |
|--------|-------|------------|-------------|
| `index()` | frontendmenu/index | frontendmenu | List menu items |
| `add()` | frontendmenu/add | frontendmenu_add | Create menu item |
| `edit()` | frontendmenu/edit/{id}` | frontendmenu_edit | Update menu item |
| `delete()` | frontendmenu/delete/{id}` | frontendmenu_delete | Delete menu item |

## Data Layer
- `frontendmenu` - Menu items (title, parentID, url, order, active)

## Notes for AI Agents
- Hierarchical menu structure with `parentID`
- `order` field controls display sequence
- Can link to pages, posts, or external URLs
- Frontend controller reads this to render navigation

